<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'লগইন')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h3 class="text-center mb-4">অ্যাডমিন লগইন</h3>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">ইমেইল</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">পাসওয়ার্ড</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">লগইন</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection