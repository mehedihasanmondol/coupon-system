<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponTemplate;
use Illuminate\Support\Facades\Storage;

class CouponTemplateController extends Controller
{
    public function index()
    {
        $templates = CouponTemplate::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'template_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'qr_x_position' => 'required|integer|min:0',
            'qr_y_position' => 'required|integer|min:0',
            'qr_size' => 'required|integer|min:50|max:500',
            'qr_url_pattern' => 'required|string',
            'coupon_number_x_position' => 'required|integer|min:0',
            'coupon_number_y_position' => 'required|integer|min:0',
            'coupon_number_font_size' => 'required|integer|min:10|max:100',
            'coupon_number_font_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'coupon_number_font_path' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('template_image')) {
            $path = $request->file('template_image')->store('templates', 'public');
            $validated['template_image'] = $path;
        }

        CouponTemplate::create($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'টেমপ্লেট সফলভাবে তৈরি হয়েছে');
    }

    public function edit(CouponTemplate $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, CouponTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'template_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'qr_x_position' => 'required|integer|min:0',
            'qr_y_position' => 'required|integer|min:0',
            'qr_size' => 'required|integer|min:50|max:500',
            'qr_url_pattern' => 'required|string',
            'coupon_number_x_position' => 'required|integer|min:0',
            'coupon_number_y_position' => 'required|integer|min:0',
            'coupon_number_font_size' => 'required|integer|min:10|max:100',
            'coupon_number_font_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'coupon_number_font_path' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('template_image')) {
            // Delete old image
            if ($template->template_image) {
                Storage::disk('public')->delete($template->template_image);
            }
            $path = $request->file('template_image')->store('templates', 'public');
            $validated['template_image'] = $path;
        }

        $template->update($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'টেমপ্লেট সফলভাবে আপডেট হয়েছে');
    }

    public function destroy(CouponTemplate $template)
    {
        if ($template->template_image) {
            Storage::disk('public')->delete($template->template_image);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'টেমপ্লেট সফলভাবে মুছে ফেলা হয়েছে');
    }

    public function toggleActive(CouponTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);

        return back()->with('success', 'টেমপ্লেট স্ট্যাটাস আপডেট হয়েছে');
    }
}
