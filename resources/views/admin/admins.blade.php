@extends('layouts.app')

@section('title', 'অ্যাডমিন ব্যবস্থাপনা')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>অ্যাডমিন ব্যবস্থাপনা</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        নতুন অ্যাডমিন যোগ করুন
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>নাম</th>
                    <th>ইমেইল</th>
                    <th>তৈরির তারিখ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">কোনো অ্যাডমিন নেই</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">নতুন অ্যাডমিন যোগ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.admins.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">নাম</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ইমেইল</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">পাসওয়ার্ড</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <small class="text-muted">কমপক্ষে ৬ অক্ষর</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary">যোগ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection