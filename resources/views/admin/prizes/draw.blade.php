<!-- resources/views/admin/prizes/draw.blade.php -->
@extends('layouts.app')

@section('title', 'পুরস্কার ঘোষণা')

@section('content')
<h2>পুরস্কার ঘোষণা</h2>
<hr>

@if($hasUnannounced)
<div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">নতুন পুরস্কার ঘোষণা করুন</h5>
    </div>
    <div class="card-body text-center">
        <p>আপনার একটি অঘোষিত পুরস্কার টেমপ্লেট আছে। নিচের বাটনে ক্লিক করে পুরস্কার ঘোষণা করুন।</p>
        <form method="POST" action="{{ route('admin.prizes.draw.start') }}" 
              onsubmit="return confirm('আপনি কি নিশ্চিত যে পুরস্কার ঘোষণা করতে চান?')">
            @csrf
            <button type="submit" class="btn btn-success btn-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-trophy-fill" viewBox="0 0 16 16">
                    <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5c0 .538-.012 1.05-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33.076 33.076 0 0 1 2.5.5z"/>
                </svg>
                পুরস্কার ঘোষণা শুরু করুন
            </button>
        </form>
    </div>
</div>
@else
<div class="alert alert-info">
    বর্তমানে কোনো অঘোষিত পুরস্কার টেমপ্লেট নেই। নতুন টেমপ্লেট তৈরি করতে 
    <a href="{{ route('admin.prizes.templates') }}">এখানে ক্লিক করুন</a>।
</div>
@endif

<h4 class="mt-4">ঘোষিত পুরস্কার তালিকা</h4>
<hr>

@forelse($templates as $template)
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $template->name }}</h5>
            <small>ঘোষণার তারিখ: {{ $template->announced_at->format('d/m/Y H:i') }}</small>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>পজিশন</th>
                        <th>পুরস্কার</th>
                        <th>কুপন নম্বর</th>
                        <th>বিজয়ীর তথ্য</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($template->winners as $winner)
                    <tr>
                        <td><strong>{{ $winner->position }}ম</strong></td>
                        <td>{{ $winner->prize_name }}</td>
                        <td><span class="badge bg-primary">{{ $winner->coupon->coupon_number }}</span></td>
                        <td>
                            @if($winner->winner_name)
                                <strong>নাম:</strong> {{ $winner->winner_name }}<br>
                                @if($winner->winner_mobile)
                                    <strong>মোবাইল:</strong> {{ $winner->winner_mobile }}<br>
                                @endif
                                @if($winner->winner_age)
                                    <strong>বয়স:</strong> {{ $winner->winner_age }}<br>
                                @endif
                                @if($winner->winner_address)
                                    <strong>ঠিকানা:</strong> {{ $winner->winner_address }}<br>
                                @endif
                                @if($winner->winner_photo)
                                    <img src="{{ asset('storage/' . $winner->winner_photo) }}" 
                                         class="img-thumbnail mt-2" style="max-width: 100px;">
                                @endif
                            @else
                                <span class="text-muted">তথ্য যোগ করা হয়নি</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                    data-bs-target="#winnerModal{{ $winner->id }}">
                                তথ্য যোগ/সম্পাদনা
                            </button>
                        </td>
                    </tr>

                    <!-- Winner Info Modal -->
                    <div class="modal fade" id="winnerModal{{ $winner->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">বিজয়ীর তথ্য (কুপন: {{ $winner->coupon->coupon_number }})</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.prizes.winner.update', $winner) }}" 
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">নাম</label>
                                            <input type="text" name="winner_name" class="form-control" 
                                                   value="{{ $winner->winner_name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">মোবাইল নম্বর</label>
                                            <input type="text" name="winner_mobile" class="form-control" 
                                                   value="{{ $winner->winner_mobile }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">বয়স</label>
                                            <input type="number" name="winner_age" class="form-control" 
                                                   value="{{ $winner->winner_age }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">ঠিকানা</label>
                                            <textarea name="winner_address" class="form-control" 
                                                      rows="3">{{ $winner->winner_address }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">ছবি</label>
                                            <input type="file" name="winner_photo" class="form-control" accept="image/*">
                                            @if($winner->winner_photo)
                                                <small class="text-muted">বর্তমান ছবি:</small>
                                                <img src="{{ asset('storage/' . $winner->winner_photo) }}" 
                                                     class="img-thumbnail mt-2" style="max-width: 150px;">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                        <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@empty
<div class="alert alert-secondary">
    এখনো কোনো পুরস্কার ঘোষণা করা হয়নি।
</div>
@endforelse
@endsection