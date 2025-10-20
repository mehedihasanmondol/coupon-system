<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $startingNumber = Setting::get('starting_number', 1);
        $secretCodeDigits = Setting::get('secret_code_digits', 6);
        $facebookPageUrl = Setting::get('facebook_page_url', '');
        
        return view('admin.settings', compact('startingNumber', 'secretCodeDigits', 'facebookPageUrl'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'starting_number' => 'required|integer|min:1',
            'secret_code_digits' => 'required|integer|min:4|max:10',
            'facebook_page_url' => 'required|url'
        ]);

        Setting::set('starting_number', $request->starting_number);
        Setting::set('secret_code_digits', $request->secret_code_digits);
        Setting::set('facebook_page_url', $request->facebook_page_url);

        return back()->with('success', 'সেটিংস আপডেট হয়েছে');
    }
}