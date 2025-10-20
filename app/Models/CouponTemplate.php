<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponTemplate extends Model
{
    protected $fillable = [
        'name',
        'template_image',
        'qr_x_position',
        'qr_y_position',
        'qr_size',
        'qr_url_pattern',
        'coupon_number_x_position',
        'coupon_number_y_position',
        'coupon_number_font_size',
        'coupon_number_font_color',
        'coupon_number_font_path',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'qr_x_position' => 'integer',
        'qr_y_position' => 'integer',
        'qr_size' => 'integer',
        'coupon_number_x_position' => 'integer',
        'coupon_number_y_position' => 'integer',
        'coupon_number_font_size' => 'integer'
    ];

    public function getTemplateImageUrlAttribute()
    {
        return asset('storage/' . $this->template_image);
    }

    public function getQrUrlForCoupon($couponCode)
    {
        return str_replace('{coupon_code}', $couponCode, $this->qr_url_pattern);
    }
}
