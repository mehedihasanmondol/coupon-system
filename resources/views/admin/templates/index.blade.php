@extends('layouts.app')

@section('title', 'কুপন টেমপ্লেট')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>কুপন টেমপ্লেট</h2>
    <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> নতুন টেমপ্লেট
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($templates->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>আইডি</th>
                            <th>নাম</th>
                            <th>টেমপ্লেট ছবি</th>
                            <th>QR সাইজ</th>
                            <th>স্ট্যাটাস</th>
                            <th>তৈরির তারিখ</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->id }}</td>
                            <td>{{ $template->name }}</td>
                            <td>
                                <img src="{{ $template->template_image_url }}" 
                                     alt="{{ $template->name }}" 
                                     style="max-width: 100px; max-height: 60px;">
                            </td>
                            <td>{{ $template->qr_size }}px</td>
                            <td>
                                <form action="{{ route('admin.templates.toggle-active', $template) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $template->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $template->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </button>
                                </form>
                            </td>
                            <td>{{ $template->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.templates.edit', $template) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> এডিট
                                </a>
                                <form action="{{ route('admin.templates.destroy', $template) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('আপনি কি নিশ্চিত?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> মুছুন
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $templates->links() }}
            </div>
        @else
            <div class="alert alert-info">
                কোন টেমপ্লেট পাওয়া যায়নি। <a href="{{ route('admin.templates.create') }}">নতুন টেমপ্লেট তৈরি করুন</a>
            </div>
        @endif
    </div>
</div>
@endsection
