<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>কুপন প্রিন্ট</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .coupon-image {
                page-break-after: always;
                page-break-inside: avoid;
            }
            .no-print {
                display: none;
            }
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .coupon-image {
            margin-bottom: 20px;
            text-align: center;
        }

        .coupon-image img {
            max-width: 100%;
            height: auto;
        }

        .print-controls {
            position: fixed;
            top: 10px;
            right: 10px;
            background: white;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #0d6efd;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button class="btn btn-primary" onclick="window.print()">
            প্রিন্ট করুন
        </button>
        <button class="btn btn-secondary" onclick="window.close()">
            বন্ধ করুন
        </button>
    </div>

    @foreach($coupons as $coupon)
        <div class="coupon-image">
            <img src="{{ route('admin.coupons.preview-image', ['coupon_id' => $coupon->id, 'template_id' => $template->id]) }}" 
                 alt="Coupon {{ $coupon->coupon_number }}">
        </div>
    @endforeach

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
