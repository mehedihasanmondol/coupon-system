<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrizeTemplate;

class PrizeTemplateController extends Controller
{
    public function index()
    {
        $templates = PrizeTemplate::orderBy('created_at', 'desc')->get();
        return view('admin.prizes.templates', compact('templates'));
    }

    public function create()
    {
        return view('admin.prizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prizes' => 'required|array|min:1',
            'prizes.*.position' => 'required|integer|min:1',
            'prizes.*.prize_name' => 'required|string'
        ]);

        PrizeTemplate::create([
            'name' => $request->name,
            'prizes' => $request->prizes,
            'is_announced' => false
        ]);

        return redirect()->route('admin.prizes.templates')->with('success', 'পুরস্কার টেমপ্লেট তৈরি হয়েছে');
    }

    public function edit(PrizeTemplate $template)
    {
        if ($template->is_announced) {
            return back()->with('error', 'ঘোষিত টেমপ্লেট সম্পাদনা করা যাবে না');
        }
        
        return view('admin.prizes.edit', compact('template'));
    }

    public function update(Request $request, PrizeTemplate $template)
    {
        if ($template->is_announced) {
            return back()->with('error', 'ঘোষিত টেমপ্লেট সম্পাদনা করা যাবে না');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'prizes' => 'required|array|min:1',
            'prizes.*.position' => 'required|integer|min:1',
            'prizes.*.prize_name' => 'required|string'
        ]);

        $template->update([
            'name' => $request->name,
            'prizes' => $request->prizes
        ]);

        return redirect()->route('admin.prizes.templates')->with('success', 'পুরস্কার টেমপ্লেট আপডেট হয়েছে');
    }

    public function destroy(PrizeTemplate $template)
    {
        if ($template->is_announced) {
            return back()->with('error', 'ঘোষিত টেমপ্লেট মুছে ফেলা যাবে না');
        }

        $template->delete();
        return back()->with('success', 'পুরস্কার টেমপ্লেট মুছে ফেলা হয়েছে');
    }
}