<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'coupon_number',
        'secret_code',
        'is_opened',
        'is_drawn',
        'opened_at'
    ];

    protected $casts = [
        'is_opened' => 'boolean',
        'is_drawn' => 'boolean',
        'opened_at' => 'datetime'
    ];

    public function prizeWinner()
    {
        return $this->hasOne(PrizeWinner::class);
    }
}
