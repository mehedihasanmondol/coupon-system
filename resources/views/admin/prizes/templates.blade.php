@extends('layouts.app')

@section('title', 'পুরস্কার টেমপ্লেট')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>পুরস্কার টেমপ্লেট</h2>
    <a href="{{ route('admin.prizes.create') }}" class="btn btn-primary">নতুন টেমপ্লেট তৈরি করুন</a>
</div>

<div class="row">
    @forelse($templates as $template)
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $template->name }}</h5>
                @if($template->is_announced)
                    <span class="badge bg-success">ঘোষিত</span>
                @else
                    <span class="badge bg-secondary">অঘোষিত</span>
                @endif
            </div>
            <div class="card-body">
                <h6>পুরস্কার তালিকা:</h6>
                <ul class="list-group mb-3">
                    @foreach($template->prizes as $prize)
                    <li class="list-group-item">
                        <strong>{{ $prize['position'] }}ম পুরস্কার:</strong> {{ $prize['prize_name'] }}
                    </li>
                    @endforeach
                </ul>
                
                @if($template->is_announced)
                    <p class="text-muted mb-0">
                        <small>ঘোষণার তারিখ: {{ $template->announced_at->format('d/m/Y H:i') }}</small>
                    </p>
                @else
                    <div class="btn-group w-100">
                        <a href="{{ route('admin.prizes.edit', $template) }}" class="btn btn-sm btn-warning">সম্পাদনা</a>
                        <form action="{{ route('admin.prizes.destroy', $template) }}" method="POST" 
                              onsubmit="return confirm('আপনি কি নিশ্চিত?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">মুছে ফেলুন</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            কোনো পুরস্কার টেমপ্লেট নেই। নতুন টেমপ্লেট তৈরি করুন।
        </div>
    </div>
    @endforelse
</div>
@endsection