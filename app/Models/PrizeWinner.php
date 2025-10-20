<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrizeWinner extends Model
{
    protected $fillable = [
        'coupon_id',
        'prize_template_id',
        'position',
        'prize_name',
        'winner_name',
        'winner_photo',
        'winner_address',
        'winner_age',
        'winner_mobile'
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function prizeTemplate()
    {
        return $this->belongsTo(PrizeTemplate::class);
    }
}
