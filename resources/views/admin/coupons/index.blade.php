@extends('layouts.app')

@section('title', 'কুপন ব্যবস্থাপনা')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>কুপন ব্যবস্থাপনা</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal">
        কুপন জেনারেট করুন
    </button>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6>মোট কুপন</h6>
                <h3>{{ $totalCoupons }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6>খোলা কুপন</h6>
                <h3>{{ $openedCoupons }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6>ড্র হওয়া কুপন</h6>
                <h3>{{ $drawnCoupons }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>কুপন নম্বর</th>
                    <th>সিক্রেট কোড</th>
                    <th>স্ট্যাটাস</th>
                    <th>খোলার সময়</th>
                    <th>তৈরির তারিখ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td><strong>{{ $coupon->coupon_number }}</strong></td>
                    <td><code>{{ $coupon->secret_code }}</code></td>
                    <td>
                        @if($coupon->is_drawn)
                            <span class="badge bg-warning">ড্র হয়েছে</span>
                        @elseif($coupon->is_opened)
                            <span class="badge bg-success">খোলা</span>
                        @else
                            <span class="badge bg-secondary">বন্ধ</span>
                        @endif
                    </td>
                    <td>{{ $coupon->opened_at ? $coupon->opened_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $coupon->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">কোনো কুপন নেই</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $coupons->links() }}
        </div>
    </div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">কুপন জেনারেট করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.coupons.generate') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">কতটি কুপন জেনারেট করতে চান?</label>
                        <input type="number" name="quantity" class="form-control" min="1" max="1000" required>
                        <small class="text-muted">সর্বোচ্চ ১০০০ টি কুপন একসাথে জেনারেট করতে পারবেন</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary">জেনারেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection