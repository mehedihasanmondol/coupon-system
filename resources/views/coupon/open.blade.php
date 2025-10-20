@extends('layouts.app')

@section('title', 'কুপন খুলুন')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body p-5">
                <h3 class="text-center mb-4">কুপন খুলুন</h3>
                
                @if(isset($coupon))
                    <div class="alert alert-info text-center">
                        <h4>কুপন নম্বর: {{ $coupon->coupon_number }}</h4>
                    </div>
                    
                    <form method="POST" action="{{ route('coupon.verify') }}">
                        @csrf
                        <input type="hidden" name="coupon_number" value="{{ $coupon->coupon_number }}">
                        
                        <div class="mb-3">
                            <label class="form-label">আপনার সিক্রেট কোড লিখুন</label>
                            <input type="text" name="secret_code" class="form-control form-control-lg text-center" 
                                   placeholder="সিক্রেট কোড" required autofocus style="letter-spacing: 5px;">
                            <small class="text-muted">সিক্রেট কোডটি বড় হাতের অক্ষরে লিখুন</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">কুপন খুলুন</button>
                    </form>
                @else
                    <div class="alert alert-warning text-center">
                        <p>কুপন খুলতে URL-এ কুপন নম্বর দিন</p>
                        <small>উদাহরণ: /coupon/open?coupon=১</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection