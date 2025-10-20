<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'কুপন সিস্টেম')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'SolaimanLipi', Arial, sans-serif; }
        .sidebar { min-height: 100vh; background: #343a40; }
        .sidebar a { color: #fff; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background: #495057; }
        .sidebar a.active { background: #007bff; }
    </style>
</head>
<body>
    @auth
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <div class="p-3">
                    <h4 class="text-white">কুপন সিস্টেম</h4>
                    <hr class="text-white">
                    <a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a>
                    <a href="{{ route('admin.coupons.index') }}">কুপন ব্যবস্থাপনা</a>
                    <a href="{{ route('admin.templates.index') }}">কুপন টেমপ্লেট</a>
                    <a href="{{ route('admin.prizes.templates') }}">পুরস্কার টেমপ্লেট</a>
                    <a href="{{ route('admin.prizes.draw') }}">পুরস্কার ঘোষণা</a>
                    <a href="{{ route('admin.settings') }}">সেটিংস</a>
                    <a href="{{ route('admin.admins') }}">অ্যাডমিন</a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">লগআউট</button>
                    </form>
                </div>
            </div>
            <div class="col-md-10">
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="container mt-5">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html