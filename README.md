# Laravel কুপন জেনারেটর সিস্টেম - ইনস্টলেশন গাইড

## ধাপ ১: Laravel প্রজেক্ট সেটআপ

```bash
# নতুন Laravel প্রজেক্ট তৈরি করুন
composer create-project laravel/laravel coupon-system

# প্রজেক্ট ডিরেক্টরিতে যান
cd coupon-system
```

## ধাপ ২: ডাটাবেস কনফিগারেশন

`.env` ফাইল খুলুন এবং ডাটাবেস সেটিংস আপডেট করুন:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coupon_system
DB_USERNAME=root
DB_PASSWORD=
```

## ধাপ ৩: Migration ফাইল তৈরি

নিচের কমান্ডগুলো রান করুন:

```bash
php artisan make:migration create_settings_table
php artisan make:migration create_coupons_table
php artisan make:migration create_prize_templates_table
php artisan make:migration create_prize_winners_table
php artisan make:migration create_users_table
```

প্রদত্ত Migration কোডগুলো সংশ্লিষ্ট ফাইলে কপি করুন।

## ধাপ ৪: Model তৈরি

```bash
php artisan make:model Setting
php artisan make:model Coupon
php artisan make:model PrizeTemplate
php artisan make:model PrizeWinner
```

প্রদত্ত Model কোডগুলো `app/Models` ডিরেক্টরিতে কপি করুন।

## ধাপ ৫: Controller তৈরি

```bash
php artisan make:controller AuthController
php artisan make:controller AdminController
php artisan make:controller SettingController
php artisan make:controller CouponController
php artisan make:controller PrizeTemplateController
php artisan make:controller PrizeDrawController
```

প্রদত্ত Controller কোডগুলো `app/Http/Controllers` ডিরেক্টরিতে কপি করুন।

## ধাপ ৬: Routes সেটআপ

`routes/web.php` ফাইলে প্রদত্ত routes কোড যোগ করুন।

## ধাপ ৭: View ফাইল তৈরি

নিচের ডিরেক্টরি স্ট্রাকচার তৈরি করুন:

```
resources/views/
├── layouts/
│   └── app.blade.php
├── auth/
│   └── login.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── settings.blade.php
│   ├── admins.blade.php
│   ├── coupons/
│   │   └── index.blade.php
│   └── prizes/
│       ├── templates.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       └── draw.blade.php
└── coupon/
    └── open.blade.php
```

প্রদত্ত view কোডগুলো সংশ্লিষ্ট ফাইলে কপি করুন।

## ধাপ ৮: Seeder সেটআপ

`database/seeders/DatabaseSeeder.php` ফাইল আপডেট করুন প্রদত্ত কোড দিয়ে।

## ধাপ ৯: Storage Link তৈরি

```bash
php artisan storage:link
```

## ধাপ ১০: Migration Run করুন

```bash
php artisan migrate --seed
```

## ধাপ ১১: অ্যাপ্লিকেশন চালু করুন

```bash
php artisan serve
```

## ডিফল্ট লগইন তথ্য

- **ইমেইল:** admin@example.com
- **পাসওয়ার্ড:** password

## প্রধান ফিচারসমূহ

### ১. কুপন ম্যানেজমেন্ট
- কুপন জেনারেট করুন (ডায়লগ বক্স থেকে সংখ্যা দিয়ে)
- স্বয়ংক্রিয় নম্বরিং (পূর্ববর্তী নম্বরের পর থেকে)
- প্রতিটি কুপনের ইউনিক সিক্রেট কোড
- কুপন স্ট্যাটাস ট্র্যাকিং (বন্ধ/খোলা/ড্র হয়েছে)

### ২. কুপন খোলার সিস্টেম
- URL: `/coupon/open?coupon=১`
- সিক্রেট কোড ভেরিফিকেশন
- সঠিক হলে ফেসবুক পেজে redirect
- ভুল হলে এরর মেসেজ
- একবার খোলা কুপন পুনরায় ব্যবহার করা যাবে না

### ৩. পুরস্কার টেমপ্লেট
- একাধিক পুরস্কার টেমপ্লেট তৈরি
- প্রতিটি টেমপ্লেটে একাধিক পুরস্কার
- পুরস্কারের পজিশন ও নাম সেট করা

### ৪. পুরস্কার ঘোষণা
- শেষ অঘোষিত টেমপ্লেট স্বয়ংক্রিয়ভাবে নির্বাচন
- খোলা ও অড্রন কুপন থেকে র‍্যান্ডম নির্বাচন
- বিজয়ীর তথ্য সংরক্ষণ (নাম, ছবি, ঠিকানা, বয়স, মোবাইল)
- একবার ঘোষিত টেমপ্লেট পুনরায় ব্যবহার হবে না

### ৫. সেটিংস
- শুরুর নম্বর সেট করা
- সিক্রেট কোড ডিজিট (৪-১০)
- ফেসবুক পেজ URL

### ৬. অ্যাডমিন ম্যানেজমেন্ট
- ডিফল্ট অ্যাডমিন
- নতুন অ্যাডমিন তৈরি করার সুবিধা

## কুপন ব্যবহারের উদাহরণ

1. অ্যাডমিন ড্যাশবোর্ডে লগইন করুন
2. "কুপন জেনারেট করুন" বাটনে ক্লিক করুন
3. যতটি কুপন চান তা লিখুন (যেমন: ১০০)
4. জেনারেট হলে প্রতিটি কুপনের একটি নম্বর ও সিক্রেট কোড থাকবে
5. কুপন খুলতে: `/coupon/open?coupon=১`
6. সিক্রেট কোড দিন
7. সঠিক হলে ফেসবুক পেজে নিয়ে যাবে

## পুরস্কার ঘোষণার উদাহরণ

1. "পুরস্কার টেমপ্লেট" থেকে নতুন টেমপ্লেট তৈরি করুন
2. পুরস্কার যোগ করুন (যেমন: ১ম - ব্যাগ, ২য় - ঘড়ি, ৩য় - অ্যালবাম)
3. "পুরস্কার ঘোষণা" পেজে যান
4. "পুরস্কার ঘোষণা শুরু করুন" বাটনে ক্লিক করুন
5. স্বয়ংক্রিয়ভাবে বিজয়ী নির্বাচিত হবে
6. বিজয়ীদের তথ্য যোগ করুন

## গুরুত্বপূর্ণ নোট

- সিক্রেট কোড বড় হাতের অক্ষরে থাকবে
- একবার খোলা কুপন পুনরায় খোলা যাবে না
- একবার ড্র হওয়া কুপন পরবর্তী ড্রতে আসবে না
- ঘোষিত টেমপ্লেট সম্পাদনা বা মুছে ফেলা যাবে না
- বিজয়ীর ছবি `storage/app/public/winners` ফোল্ডারে সংরক্ষিত হবে