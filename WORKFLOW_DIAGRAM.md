# কুপন ইমেজ জেনারেটর - ওয়ার্কফ্লো ডায়াগ্রাম

## 📋 সম্পূর্ণ ওয়ার্কফ্লো

```
┌─────────────────────────────────────────────────────────────────┐
│                    COUPON IMAGE GENERATOR                        │
│                         WORKFLOW                                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ STEP 1: TEMPLATE SETUP                                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Create Template  │
                    │  - Upload Image  │
                    │  - Set QR Pos    │
                    │  - Set Text Pos  │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Save Template   │
                    │   to Database    │
                    └──────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ STEP 2: IMAGE GENERATION                                        │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Select Template  │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Select Coupons   │
                    │  (Multi-select)  │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Choose Action   │
                    └──────────────────┘
                              │
                ┌─────────────┼─────────────┐
                │             │             │
                ▼             ▼             ▼
         ┌──────────┐  ┌──────────┐  ┌──────────┐
         │ Preview  │  │ Download │  │  Print   │
         └──────────┘  └──────────┘  └──────────┘

┌─────────────────────────────────────────────────────────────────┐
│ STEP 3: IMAGE PROCESSING (Backend)                             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │ Load Template    │
                    │     Image        │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Generate QR     │
                    │  Code for URL    │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Place QR Code   │
                    │  at Position     │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Add Coupon      │
                    │  Number Text     │
                    └──────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Return PNG      │
                    │     Image        │
                    └──────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ STEP 4: OUTPUT                                                  │
└─────────────────────────────────────────────────────────────────┘
                              │
                ┌─────────────┼─────────────┐
                │             │             │
                ▼             ▼             ▼
         ┌──────────┐  ┌──────────┐  ┌──────────┐
         │  Show    │  │ Create   │  │  Open    │
         │ Preview  │  │   ZIP    │  │  Print   │
         │  Modal   │  │  & Send  │  │  Window  │
         └──────────┘  └──────────┘  └──────────┘
```

---

## 🔄 ডাটা ফ্লো

```
┌──────────────┐
│   Browser    │
│   (User)     │
└──────┬───────┘
       │
       │ 1. Select Template & Coupons
       │
       ▼
┌──────────────────────────────────────┐
│     CouponController                 │
│  - showImageGenerator()              │
│  - generateImages()                  │
│  - previewImage()                    │
└──────┬───────────────────────────────┘
       │
       │ 2. Fetch Data
       │
       ▼
┌──────────────────────────────────────┐
│     Database                         │
│  - coupon_templates                  │
│  - coupons                           │
└──────┬───────────────────────────────┘
       │
       │ 3. Process Images
       │
       ▼
┌──────────────────────────────────────┐
│   Image Processing Layer             │
│  - Intervention Image                │
│  - Endroid QR Code                   │
└──────┬───────────────────────────────┘
       │
       │ 4. Generate Output
       │
       ▼
┌──────────────────────────────────────┐
│     File System                      │
│  - storage/app/temp/                 │
│  - storage/app/public/templates/     │
└──────┬───────────────────────────────┘
       │
       │ 5. Return to User
       │
       ▼
┌──────────────┐
│   Browser    │
│  (Download/  │
│   Print)     │
└──────────────┘
```

---

## 🎨 টেমপ্লেট স্ট্রাকচার

```
┌─────────────────────────────────────────────────────┐
│                                                     │
│  ┌──────────┐                                       │
│  │          │  ← QR Code Position (X, Y)           │
│  │  QR CODE │     Size: Configurable               │
│  │          │                                       │
│  └──────────┘                                       │
│                                                     │
│                                                     │
│         COUPON #12345  ← Coupon Number             │
│                          Position (X, Y)           │
│                          Font Size & Color         │
│                                                     │
│                                                     │
│         [Your Design Here]                         │
│                                                     │
│                                                     │
└─────────────────────────────────────────────────────┘
         Template Base Image
```

---

## 📂 ফাইল স্ট্রাকচার

```
coupon-system/
│
├── app/
│   ├── Http/Controllers/
│   │   ├── CouponController.php ✅
│   │   └── CouponTemplateController.php ✅
│   │
│   └── Models/
│       └── CouponTemplate.php ✅
│
├── database/
│   ├── migrations/
│   │   └── 2025_10_20_094000_create_coupon_templates_table.php ✅
│   │
│   └── seeders/
│       └── CouponTemplateSeeder.php ✅
│
├── resources/views/
│   ├── admin/
│   │   ├── templates/
│   │   │   ├── index.blade.php ✅
│   │   │   ├── create.blade.php ✅
│   │   │   └── edit.blade.php ✅
│   │   │
│   │   └── coupons/
│   │       ├── image-generator.blade.php ✅
│   │       └── print.blade.php ✅
│   │
│   └── layouts/
│       └── app.blade.php ✅ (Updated)
│
├── routes/
│   └── web.php ✅ (Updated)
│
├── storage/
│   └── app/
│       ├── public/
│       │   ├── templates/ ← Upload here
│       │   └── fonts/ ← Custom fonts
│       │
│       └── temp/ ← Temporary files
│
├── composer.json ✅ (Updated)
│
└── Documentation/
    ├── QUICK_START.md ✅
    ├── SETUP_INSTRUCTIONS.md ✅
    ├── COUPON_IMAGE_GENERATOR_GUIDE.md ✅
    ├── IMPLEMENTATION_SUMMARY.md ✅
    ├── FINAL_CHECKLIST.md ✅
    └── WORKFLOW_DIAGRAM.md ✅ (This file)
```

---

## 🎯 ইউজার জার্নি

### Admin User Journey

```
1. Login to Admin Panel
   │
   ├─→ 2a. Create Template
   │      │
   │      ├─→ Upload template image
   │      ├─→ Set QR code settings
   │      ├─→ Set coupon number settings
   │      └─→ Save template
   │
   └─→ 2b. Generate Images
          │
          ├─→ Select template
          ├─→ Select coupons (multi)
          │
          ├─→ 3a. Preview
          │      └─→ Check positioning
          │
          ├─→ 3b. Download ZIP
          │      └─→ Get all images
          │
          └─→ 3c. Print
                 └─→ Print directly
```

### End User Journey (QR Code)

```
1. Receive Physical Coupon
   │
   └─→ 2. Scan QR Code
          │
          └─→ 3. Redirect to URL
                 │
                 └─→ 4. Coupon Verification Page
                        │
                        └─→ 5. Enter Secret Code
                               │
                               └─→ 6. Success/Redirect
```

---

## 🔧 টেকনিক্যাল ফ্লো

### Image Generation Process

```
Input: Coupon ID + Template ID
│
├─→ Load Template from DB
│   └─→ Get settings (positions, sizes, colors)
│
├─→ Load Template Image
│   └─→ From: storage/app/public/templates/
│
├─→ Generate QR Code
│   ├─→ Create URL with coupon code
│   ├─→ Generate QR image
│   └─→ Set size from template
│
├─→ Composite Image
│   ├─→ Place QR code at (X, Y)
│   └─→ Add coupon number text
│       ├─→ Position (X, Y)
│       ├─→ Font size
│       ├─→ Font color
│       └─→ Optional: Custom font
│
└─→ Output: PNG Image
    ├─→ Preview: Direct display
    ├─→ Download: Save to temp + ZIP
    └─→ Print: Render in print view
```

---

## 📊 ডাটাবেস রিলেশনশিপ

```
┌─────────────────┐
│  coupon_        │
│  templates      │
│─────────────────│
│ id (PK)         │
│ name            │
│ template_image  │
│ qr_x_position   │
│ qr_y_position   │
│ qr_size         │
│ qr_url_pattern  │
│ ...             │
└─────────────────┘
        │
        │ (Used by)
        │
        ▼
┌─────────────────┐
│    coupons      │
│─────────────────│
│ id (PK)         │
│ coupon_number   │
│ secret_code     │
│ is_opened       │
│ ...             │
└─────────────────┘
```

---

## 🚀 পারফরম্যান্স কনসিডারেশন

```
Single Image:
  Template Load → QR Generate → Composite → Output
  Time: ~100-200ms

Batch (10 images):
  Loop 10 times → Create ZIP → Output
  Time: ~1-2 seconds

Batch (100 images):
  Loop 100 times → Create ZIP → Output
  Time: ~10-20 seconds
  
Recommendation:
  - For >50 images: Consider queue jobs
  - For >100 images: Show progress bar
  - Optimize: Cache template image
```

---

## ✅ সিকিউরিটি লেয়ার

```
┌─────────────────────────────────────┐
│  Authentication Middleware          │
│  (All admin routes protected)       │
└─────────────────┬───────────────────┘
                  │
                  ▼
┌─────────────────────────────────────┐
│  Input Validation                   │
│  - File type check (image)          │
│  - File size limit (5MB)            │
│  - Position value validation        │
└─────────────────┬───────────────────┘
                  │
                  ▼
┌─────────────────────────────────────┐
│  File Storage Security              │
│  - Stored in protected directory    │
│  - Served via storage link          │
│  - Temp files auto-cleanup          │
└─────────────────────────────────────┘
```

---

## 🎉 সম্পন্ন!

এই ওয়ার্কফ্লো ডায়াগ্রাম আপনাকে সম্পূর্ণ সিস্টেম বুঝতে সাহায্য করবে।

**পরবর্তী পদক্ষেপ:**
1. ✅ QUICK_START.md পড়ুন
2. ✅ প্রথম টেমপ্লেট তৈরি করুন
3. ✅ টেস্ট করুন
4. ✅ উৎপাদনে ব্যবহার করুন!
