@extends('layouts.app')

@section('title', 'সেটিংস')

@section('content')
<h2>সিস্টেম সেটিংস</h2>
<hr>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            
            <div class="mb-4">
                <label class="form-label">কুপন নম্বর শুরুর সংখ্যা</label>
                <input type="number" name="starting_number" class="form-control" 
                       value="{{ $startingNumber }}" min="1" required>
                <small class="text-muted">প্রথম কুপন কোন নম্বর থেকে শুরু হবে (শুধুমাত্র প্রথম জেনারেশনের জন্য প্রযোজ্য)</small>
            </div>

            <div class="mb-4">
                <label class="form-label">সিক্রেট কোড ডিজিট</label>
                <input type="number" name="secret_code_digits" class="form-control" 
                       value="{{ $secretCodeDigits }}" min="4" max="10" required>
                <small class="text-muted">সিক্রেট কোড কত ডিজিটের হবে (৪-১০ এর মধ্যে)</small>
            </div>

            <div class="mb-4">
                <label class="form-label">ফেসবুক পেজ URL</label>
                <input type="url" name="facebook_page_url" class="form-control" 
                       value="{{ $facebookPageUrl }}" required>
                <small class="text-muted">কুপন খোলার পর যে ফেসবুক পেজে redirect করবে</small>
            </div>

            <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
        </form>
    </div>
</div>
@endsection
