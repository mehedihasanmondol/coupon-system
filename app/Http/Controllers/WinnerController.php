<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrizeTemplate;

class WinnerController extends Controller
{
    public function index()
    {
        $announcements = PrizeTemplate::where('is_announced', true)
            ->with(['winners' => function($query) {
                $query->orderBy('position', 'asc');
            }, 'winners.coupon'])
            ->orderBy('announced_at', 'desc')
            ->get();
            
        return view('winners.index', compact('announcements'));
    }
}