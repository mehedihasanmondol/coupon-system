<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CouponTemplate;

class CouponTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample template - You'll need to upload an actual image
        CouponTemplate::create([
            'name' => 'ডিফল্ট টেমপ্লেট',
            'template_image' => 'templates/sample-template.png', // You need to upload this
            'qr_x_position' => 50,
            'qr_y_position' => 50,
            'qr_size' => 150,
            'qr_url_pattern' => 'https://example.com/coupon?code={coupon_code}',
            'coupon_number_x_position' => 250,
            'coupon_number_y_position' => 100,
            'coupon_number_font_size' => 32,
            'coupon_number_font_color' => '#000000',
            'coupon_number_font_path' => null,
            'is_active' => true
        ]);
    }
}
