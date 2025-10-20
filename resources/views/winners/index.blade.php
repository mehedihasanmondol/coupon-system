<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পুরস্কার বিজয়ীদের তালিকা</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'SolaimanLipi', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .header-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .header-section h1 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .header-section p {
            color: #666;
            font-size: 1.1rem;
        }
        .announcement-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .announcement-card:hover {
            transform: translateY(-5px);
        }
        .announcement-header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .announcement-title {
            color: #667eea;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .announcement-date {
            color: #999;
            font-size: 0.9rem;
        }
        .winner-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid #667eea;
            transition: all 0.3s;
        }
        .winner-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-left-width: 8px;
        }
        .position-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        .position-1 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .position-2 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        .position-3 {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
        .position-other {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        .prize-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .coupon-number {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .winner-info {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .winner-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            margin-bottom: 10px;
        }
        .info-row {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #667eea;
        }
        .no-winners {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .no-winners-icon {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        .footer-link {
            text-align: center;
            margin-top: 30px;
        }
        .footer-link a {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 10px 30px;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .footer-link a:hover {
            background: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h1>🏆 পুরস্কার বিজয়ীদের তালিকা</h1>
            <p>আমাদের সকল পুরস্কার বিজয়ীদের অভিনন্দন!</p>
        </div>

        @forelse($announcements as $announcement)
        <div class="announcement-card">
            <div class="announcement-header">
                <div class="announcement-title">{{ $announcement->name }}</div>
                <div class="announcement-date">
                    📅 ঘোষণার তারিখ: {{ $announcement->announced_at->format('d F Y, h:i A') }}
                </div>
            </div>

            <div class="row">
                @foreach($announcement->winners as $winner)
                <div class="col-md-6 mb-3">
                    <div class="winner-card">
                        <span class="position-badge position-{{ min($winner->position, 3) == $winner->position ? $winner->position : 'other' }}">
                            {{ $winner->position }}{{ $winner->position == 1 ? 'ম' : ($winner->position == 2 ? 'য়' : 'য়') }} পুরস্কার
                        </span>
                        
                        <div class="prize-name">🎁 {{ $winner->prize_name }}</div>
                        
                        <div class="coupon-number">
                            কুপন নম্বর: {{ $winner->coupon->coupon_number }}
                        </div>

                        @if($winner->winner_name || $winner->winner_photo)
                        <div class="winner-info">
                            @if($winner->winner_photo)
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $winner->winner_photo) }}" 
                                     alt="বিজয়ীর ছবি" class="winner-photo">
                            </div>
                            @endif

                            @if($winner->winner_name)
                            <div class="info-row">
                                <span class="info-label">নাম:</span>
                                <span>{{ $winner->winner_name }}</span>
                            </div>
                            @endif

                            @if($winner->winner_age)
                            <div class="info-row">
                                <span class="info-label">বয়স:</span>
                                <span>{{ $winner->winner_age }} বছর</span>
                            </div>
                            @endif

                            @if($winner->winner_address)
                            <div class="info-row">
                                <span class="info-label">ঠিকানা:</span>
                                <span>{{ $winner->winner_address }}</span>
                            </div>
                            @endif

                            @if($winner->winner_mobile)
                            <div class="info-row">
                                <span class="info-label">মোবাইল:</span>
                                <span>{{ $winner->winner_mobile }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="no-winners">
            <div class="no-winners-icon">🎯</div>
            <h3>এখনো কোনো পুরস্কার ঘোষণা করা হয়নি</h3>
            <p class="text-muted">শীঘ্রই পুরস্কার ঘোষণা করা হবে। অপেক্ষা করুন...</p>
        </div>
        @endforelse

        <div class="footer-link">
            <a href="{{ route('login') }}">অ্যাডমিন লগইন →</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>