<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrizeTemplate extends Model
{
    protected $fillable = [
        'name',
        'prizes',
        'is_announced',
        'announced_at'
    ];

    protected $casts = [
        'prizes' => 'array',
        'is_announced' => 'boolean',
        'announced_at' => 'datetime'
    ];

    public function winners()
    {
        return $this->hasMany(PrizeWinner::class);
    }
}
