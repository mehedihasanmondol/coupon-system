<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\CouponTemplate;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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

    public function showImageGenerator()
    {
        $coupons = Coupon::orderBy('coupon_number', 'desc')->paginate(50);
        $templates = CouponTemplate::where('is_active', true)->get();
        
        return view('admin.coupons.image-generator', compact('coupons', 'templates'));
    }

    public function generateImages(Request $request)
    {
        $request->validate([
            'coupon_ids' => 'required|array|min:1',
            'coupon_ids.*' => 'exists:coupons,id',
            'template_id' => 'required|exists:coupon_templates,id'
        ], [
            'coupon_ids.required' => 'অন্তত একটি কুপন নির্বাচন করুন',
            'coupon_ids.min' => 'অন্তত একটি কুপন নির্বাচন করুন',
            'template_id.required' => 'একটি টেমপ্লেট নির্বাচন করুন'
        ]);

        $coupons = Coupon::whereIn('id', $request->coupon_ids)->get();
        $template = CouponTemplate::findOrFail($request->template_id);

        // Ensure temp directory exists
        $baseTempDir = storage_path('app/temp');
        if (!file_exists($baseTempDir)) {
            mkdir($baseTempDir, 0755, true);
        }

        // Create temporary directory for generated images
        $tempDir = storage_path('app/temp/coupon-images-' . time());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $manager = new ImageManager(new Driver());
        $generatedFiles = [];

        foreach ($coupons as $coupon) {
            try {
                $imagePath = $this->generateCouponImage($coupon, $template, $manager);
                $fileName = 'coupon-' . $coupon->coupon_number . '.png';
                $filePath = $tempDir . '/' . $fileName;
                
                // Save the generated image
                file_put_contents($filePath, $imagePath);
                $generatedFiles[] = $filePath;
            } catch (\Exception $e) {
                \Log::error('Error generating image for coupon ' . $coupon->coupon_number . ': ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
            }
        }

        // Check if any files were generated
        if (empty($generatedFiles)) {
            return back()->with('error', 'কোন ইমেজ জেনারেট করা যায়নি। লগ চেক করুন।');
        }

        // Create ZIP file
        $zipFileName = 'coupons-' . time() . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($generatedFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        } else {
            \Log::error('Failed to create ZIP file at: ' . $zipPath);
            return back()->with('error', 'ZIP ফাইল তৈরি করতে ব্যর্থ হয়েছে।');
        }

        // Clean up temporary files
        foreach ($generatedFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        if (file_exists($tempDir)) {
            rmdir($tempDir);
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function previewImage(Request $request)
    {
        $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
            'template_id' => 'required|exists:coupon_templates,id'
        ]);

        $coupon = Coupon::findOrFail($request->coupon_id);
        $template = CouponTemplate::findOrFail($request->template_id);

        $manager = new ImageManager(new Driver());
        $imageData = $this->generateCouponImage($coupon, $template, $manager);

        return response($imageData)->header('Content-Type', 'image/png');
    }

    private function generateCouponImage($coupon, $template, $manager)
    {
        // Load template image
        $templatePath = storage_path('app/public/' . $template->template_image);
        $image = $manager->read($templatePath);

        // Generate QR Code
        $qrUrl = $template->getQrUrlForCoupon($coupon->coupon_number);
        
        // Create QR code - v6 uses constructor only
        $qrCode = new QrCode($qrUrl);
        
        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode);
        
        // Get QR code as data URI and read with Intervention
        $qrDataUri = $qrResult->getDataUri();
        $qrImage = $manager->read($qrDataUri);
        
        // Resize QR code to template size
        $qrImage->scale($template->qr_size, $template->qr_size);

        // Place QR code on template
        $image->place($qrImage, 'top-left', $template->qr_x_position, $template->qr_y_position);

        // Add coupon number text - using native GD for font size control
        $core = $image->core()->native();
        
        $fontSize = (int)$template->coupon_number_font_size;
        $textX = (int)$template->coupon_number_x_position;
        $textY = (int)$template->coupon_number_y_position + $fontSize; // Adjust Y for baseline
        $couponText = (string)$coupon->coupon_number;
        
        // Convert hex color to RGB for GD
        $color = $this->hexToRgb($template->coupon_number_font_color);
        $gdColor = imagecolorallocate($core, $color['r'], $color['g'], $color['b']);
        
        // Use TTF font for proper size control
        $fontPath = $template->coupon_number_font_path 
            ? storage_path('app/public/' . $template->coupon_number_font_path)
            : null;
            
        // Try to find a system font if custom font not provided
        if (!$fontPath || !file_exists($fontPath)) {
            // Try common Windows font paths
            $systemFonts = [
                'C:/Windows/Fonts/arial.ttf',
                'C:/Windows/Fonts/calibri.ttf',
                'C:/Windows/Fonts/verdana.ttf',
                'C:/Windows/Fonts/tahoma.ttf',
            ];
            
            foreach ($systemFonts as $sysFont) {
                if (file_exists($sysFont)) {
                    $fontPath = $sysFont;
                    break;
                }
            }
        }
        
        if ($fontPath && file_exists($fontPath)) {
            // Use TTF font with imagettftext for proper size control
            imagettftext($core, $fontSize, 0, $textX, $textY, $gdColor, $fontPath, $couponText);
        } else {
            // Fallback: Use built-in font with larger rendering
            $gdFontSize = 5; // Maximum built-in font size
            imagestring($core, $gdFontSize, $textX, $textY - 15, $couponText, $gdColor);
        }

        return $image->toPng();
    }

    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }

    public function printImages(Request $request)
    {
        $request->validate([
            'coupon_ids' => 'required|array|min:1',
            'coupon_ids.*' => 'exists:coupons,id',
            'template_id' => 'required|exists:coupon_templates,id'
        ], [
            'coupon_ids.required' => 'অন্তত একটি কুপন নির্বাচন করুন',
            'coupon_ids.min' => 'অন্তত একটি কুপন নির্বাচন করুন',
            'template_id.required' => 'একটি টেমপ্লেট নির্বাচন করুন'
        ]);

        $coupons = Coupon::whereIn('id', $request->coupon_ids)->get();
        $template = CouponTemplate::findOrFail($request->template_id);

        return view('admin.coupons.print', compact('coupons', 'template'));
    }
}