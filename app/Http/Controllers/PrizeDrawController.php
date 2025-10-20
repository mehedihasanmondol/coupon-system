<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrizeTemplate;
use App\Models\Coupon;
use App\Models\PrizeWinner;

class PrizeDrawController extends Controller
{
    public function index()
    {
        $templates = PrizeTemplate::where('is_announced', true)
            ->with('winners.coupon')
            ->orderBy('announced_at', 'desc')
            ->get();
            
        $hasUnannounced = PrizeTemplate::where('is_announced', false)->exists();
        
        return view('admin.prizes.draw', compact('templates', 'hasUnannounced'));
    }

    public function draw(Request $request)
    {
        // শেষ অঘোষিত টেমপ্লেট খুঁজে বের করা
        $template = PrizeTemplate::where('is_announced', false)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$template) {
            return back()->with('error', 'কোনো অঘোষিত টেমপ্লেট নেই');
        }

        // খোলা এবং এখনো ড্র হয়নি এমন কুপন খুঁজে বের করা
        $eligibleCoupons = Coupon::where('is_opened', true)
            ->where('is_drawn', false)
            ->pluck('id')
            ->toArray();

        if (count($eligibleCoupons) < count($template->prizes)) {
            return back()->with('error', 'পর্যাপ্ত খোলা কুপন নেই');
        }

        // র‍্যান্ডম কুপন নির্বাচন
        $selectedCouponIds = collect($eligibleCoupons)
            ->shuffle()
            ->take(count($template->prizes))
            ->values()
            ->all();

        // পুরস্কার বিজয়ী তৈরি করা
        foreach ($template->prizes as $index => $prize) {
            $couponId = $selectedCouponIds[$index];
            
            PrizeWinner::create([
                'coupon_id' => $couponId,
                'prize_template_id' => $template->id,
                'position' => $prize['position'],
                'prize_name' => $prize['prize_name']
            ]);

            // কুপন ড্র হিসেবে চিহ্নিত করা
            Coupon::where('id', $couponId)->update(['is_drawn' => true]);
        }

        // টেমপ্লেট ঘোষিত হিসেবে চিহ্নিত করা
        $template->update([
            'is_announced' => true,
            'announced_at' => now()
        ]);

        return back()->with('success', 'পুরস্কার ঘোষণা সফল হয়েছে');
    }

    public function updateWinner(Request $request, PrizeWinner $winner)
    {
        $request->validate([
            'winner_name' => 'nullable|string|max:255',
            'winner_photo' => 'nullable|image|max:2048',
            'winner_address' => 'nullable|string',
            'winner_age' => 'nullable|integer|min:1|max:150',
            'winner_mobile' => 'nullable|string|max:20'
        ]);

        $data = $request->only(['winner_name', 'winner_address', 'winner_age', 'winner_mobile']);

        if ($request->hasFile('winner_photo')) {
            $path = $request->file('winner_photo')->store('winners', 'public');
            $data['winner_photo'] = $path;
        }

        $winner->update($data);

        return back()->with('success', 'বিজয়ীর তথ্য আপডেট হয়েছে');
    }
}
