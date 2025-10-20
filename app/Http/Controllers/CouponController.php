<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Coupon;
use App\Models\Setting;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('coupon_number', 'desc')->paginate(50);
        $totalCoupons = Coupon::count();
        $openedCoupons = Coupon::where('is_opened', true)->count();
        $drawnCoupons = Coupon::where('is_drawn', true)->count();
        
        return view('admin.coupons.index', compact('coupons', 'totalCoupons', 'openedCoupons', 'drawnCoupons'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:1000'
        ]);

        $quantity = $request->quantity;
        $startingNumber = Setting::get('starting_number', 1);
        $secretCodeDigits = Setting::get('secret_code_digits', 6);
        
        // শেষ কুপন নম্বর খুঁজে বের করা
        $lastCoupon = Coupon::orderBy('coupon_number', 'desc')->first();
        $nextNumber = $lastCoupon ? (int)$lastCoupon->coupon_number + 1 : $startingNumber;

        $generated = 0;
        for ($i = 0; $i < $quantity; $i++) {
            $couponNumber = $nextNumber + $i;
            $secretCode = $this->generateSecretCode($secretCodeDigits);

            Coupon::create([
                'coupon_number' => (string)$couponNumber,
                'secret_code' => $secretCode,
                'is_opened' => false,
                'is_drawn' => false
            ]);

            $generated++;
        }

        return back()->with('success', "$generated টি কুপন জেনারেট হয়েছে");
    }

    private function generateSecretCode($digits)
    {
        return strtoupper(Str::random($digits));
    }

    public function showOpen(Request $request)
    {
        $couponNumber = $request->query('coupon');
        
        if (!$couponNumber) {
            return view('coupon.open')->with('error', 'কুপন নম্বর প্রদান করুন');
        }

        $coupon = Coupon::where('coupon_number', $couponNumber)->first();
        
        if (!$coupon) {
            return view('coupon.open')->with('error', 'কুপন পাওয়া যায়নি');
        }

        if ($coupon->is_opened) {
            return view('coupon.open')->with('error', 'এই কুপনটি ইতিমধ্যে খোলা হয়েছে');
        }

        return view('coupon.open', compact('coupon'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'coupon_number' => 'required',
            'secret_code' => 'required'
        ]);

        $coupon = Coupon::where('coupon_number', $request->coupon_number)->first();

        if (!$coupon) {
            return back()->with('error', 'কুপন পাওয়া যায়নি');
        }

        if ($coupon->is_opened) {
            return back()->with('error', 'এই কুপনটি ইতিমধ্যে খোলা হয়েছে');
        }

        if ($coupon->secret_code !== strtoupper($request->secret_code)) {
            return back()->with('error', 'সিক্রেট কোড সঠিক নয়। দয়া করে সঠিক কোড দিন।');
        }

        // কুপন খোলা হিসেবে চিহ্নিত করা
        $coupon->update([
            'is_opened' => true,
            'opened_at' => now()
        ]);

        $facebookUrl = Setting::get('facebook_page_url', 'https://www.facebook.com');
        
        return redirect($facebookUrl);
    }
}