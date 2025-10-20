<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড')

@section('content')
<h2>ড্যাশবোর্ড</h2>
<hr>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">মোট কুপন</h5>
                <h2>{{ \App\Models\Coupon::count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">খোলা কুপন</h5>
                <h2>{{ \App\Models\Coupon::where('is_opened', true)->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">ড্র হওয়া কুপন</h5>
                <h2>{{ \App\Models\Coupon::where('is_drawn', true)->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">মোট পুরস্কার</h5>
                <h2>{{ \App\Models\PrizeWinner::count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>দ্রুত অ্যাক্সেস</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-primary mb-2 w-100">কুপন ব্যবস্থাপনা</a>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-info mb-2 w-100">কুপন টেমপ্লেট</a>
                <a href="{{ route('admin.prizes.create') }}" class="btn btn-success mb-2 w-100">নতুন পুরস্কার টেমপ্লেট</a>
                <a href="{{ route('admin.prizes.draw') }}" class="btn btn-warning w-100">পুরস্কার ঘোষণা করুন</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>সাম্প্রতিক কুপন</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>কুপন নং</th>
                            <th>স্ট্যাটাস</th>
                            <th>তারিখ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Coupon::orderBy('created_at', 'desc')->limit(5)->get() as $coupon)
                        <tr>
                            <td>{{ $coupon->coupon_number }}</td>
                            <td>
                                @if($coupon->is_drawn)
                                    <span class="badge bg-warning">ড্র হয়েছে</span>
                                @elseif($coupon->is_opened)
                                    <span class="badge bg-success">খোলা</span>
                                @else
                                    <span class="badge bg-secondary">বন্ধ</span>
                                @endif
                            </td>
                            <td>{{ $coupon->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection