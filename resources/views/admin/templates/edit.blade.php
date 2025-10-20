@extends('layouts.app')

@section('title', 'টেমপ্লেট এডিট করুন')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>টেমপ্লেট এডিট করুন</h2>
    <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> ফিরে যান
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">টেমপ্লেট নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $template->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="template_image" class="form-label">টেমপ্লেট ছবি</label>
                        <input type="file" class="form-control @error('template_image') is-invalid @enderror" 
                               id="template_image" name="template_image" accept="image/*">
                        <small class="text-muted">সর্বোচ্চ সাইজ: 5MB (JPG, PNG) - নতুন ছবি আপলোড করতে চাইলে সিলেক্ট করুন</small>
                        @error('template_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($template->template_image)
                            <div class="mt-2">
                                <img src="{{ $template->template_image_url }}" alt="Current Template" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="qr_url_pattern" class="form-label">QR কোড URL প্যাটার্ন <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('qr_url_pattern') is-invalid @enderror" 
                               id="qr_url_pattern" name="qr_url_pattern" 
                               value="{{ old('qr_url_pattern', $template->qr_url_pattern) }}" required>
                        <small class="text-muted">{coupon_code} দিয়ে কুপন নম্বর রিপ্লেস হবে</small>
                        @error('qr_url_pattern')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <h5>QR কোড সেটিংস</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="qr_x_position" class="form-label">X পজিশন <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qr_x_position') is-invalid @enderror" 
                                       id="qr_x_position" name="qr_x_position" 
                                       value="{{ old('qr_x_position', $template->qr_x_position) }}" required>
                                @error('qr_x_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="qr_y_position" class="form-label">Y পজিশন <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qr_y_position') is-invalid @enderror" 
                                       id="qr_y_position" name="qr_y_position" 
                                       value="{{ old('qr_y_position', $template->qr_y_position) }}" required>
                                @error('qr_y_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="qr_size" class="form-label">সাইজ (px) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qr_size') is-invalid @enderror" 
                                       id="qr_size" name="qr_size" 
                                       value="{{ old('qr_size', $template->qr_size) }}" required>
                                @error('qr_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-3">কুপন নম্বর সেটিংস</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="coupon_number_x_position" class="form-label">X পজিশন <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('coupon_number_x_position') is-invalid @enderror" 
                                       id="coupon_number_x_position" name="coupon_number_x_position" 
                                       value="{{ old('coupon_number_x_position', $template->coupon_number_x_position) }}" required>
                                @error('coupon_number_x_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="coupon_number_y_position" class="form-label">Y পজিশন <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('coupon_number_y_position') is-invalid @enderror" 
                                       id="coupon_number_y_position" name="coupon_number_y_position" 
                                       value="{{ old('coupon_number_y_position', $template->coupon_number_y_position) }}" required>
                                @error('coupon_number_y_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="coupon_number_font_size" class="form-label">ফন্ট সাইজ <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('coupon_number_font_size') is-invalid @enderror" 
                                       id="coupon_number_font_size" name="coupon_number_font_size" 
                                       value="{{ old('coupon_number_font_size', $template->coupon_number_font_size) }}" required>
                                @error('coupon_number_font_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="coupon_number_font_color" class="form-label">ফন্ট কালার <span class="text-danger">*</span></label>
                                <input type="color" class="form-control @error('coupon_number_font_color') is-invalid @enderror" 
                                       id="coupon_number_font_color" name="coupon_number_font_color" 
                                       value="{{ old('coupon_number_font_color', $template->coupon_number_font_color) }}" required>
                                @error('coupon_number_font_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="coupon_number_font_path" class="form-label">কাস্টম ফন্ট পাথ (ঐচ্ছিক)</label>
                        <input type="text" class="form-control @error('coupon_number_font_path') is-invalid @enderror" 
                               id="coupon_number_font_path" name="coupon_number_font_path" 
                               value="{{ old('coupon_number_font_path', $template->coupon_number_font_path) }}" 
                               placeholder="fonts/custom-font.ttf">
                        <small class="text-muted">storage/app/public থেকে রিলেটিভ পাথ</small>
                        @error('coupon_number_font_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                               value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            সক্রিয়
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> আপডেট করুন
                </button>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                    বাতিল করুন
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
