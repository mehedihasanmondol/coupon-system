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
                    <th width="200">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>
                        {{ $admin->name }}
                        @if($admin->id === auth()->id())
                            <span class="badge bg-primary">আপনি</span>
                        @endif
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editAdminModal{{ $admin->id }}">
                            <i class="bi bi-pencil"></i> এডিট
                        </button>
                        @if($admin->id !== auth()->id() && $admins->count() > 1)
                        <button class="btn btn-sm btn-danger" 
                                onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}')">
                            <i class="bi bi-trash"></i> ডিলিট
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">কোনো অ্যাডমিন নেই</td>
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

<!-- Edit Admin Modals -->
@foreach($admins as $admin)
<div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">অ্যাডমিন এডিট করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">নাম</label>
                        <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ইমেইল</label>
                        <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">পাসওয়ার্ড</label>
                        <input type="password" name="password" class="form-control" minlength="6">
                        <small class="text-muted">পাসওয়ার্ড পরিবর্তন করতে চাইলে নতুন পাসওয়ার্ড দিন (কমপক্ষে ৬ অক্ষর)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(adminId, adminName) {
    if (confirm(`আপনি কি নিশ্চিত যে "${adminName}" কে ডিলিট করতে চান?`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/admins/${adminId}`;
        form.submit();
    }
}
</script>
@endsection