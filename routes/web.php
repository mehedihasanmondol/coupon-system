<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CouponTemplateController;
use App\Http\Controllers\PrizeTemplateController;
use App\Http\Controllers\PrizeDrawController;
use App\Http\Controllers\WinnerController;

// Public Routes
Route::get('/', function () {
    return redirect('/winners');
});

Route::get('/winners', [App\Http\Controllers\WinnerController::class, 'index'])->name('winners.index');

Route::get('/coupon/open', [CouponController::class, 'showOpen'])->name('coupon.open');
Route::post('/coupon/verify', [CouponController::class, 'verify'])->name('coupon.verify');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Management
    Route::get('/admins', [AdminController::class, 'admins'])->name('admin.admins');
    Route::post('/admins', [AdminController::class, 'createAdmin'])->name('admin.admins.create');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    
    // Coupons
    Route::get('/coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::post('/coupons/generate', [CouponController::class, 'generate'])->name('admin.coupons.generate');
    
    // Coupon Image Generator
    Route::get('/coupons/image-generator', [CouponController::class, 'showImageGenerator'])->name('admin.coupons.image-generator');
    Route::post('/coupons/generate-images', [CouponController::class, 'generateImages'])->name('admin.coupons.generate-images');
    Route::get('/coupons/preview-image', [CouponController::class, 'previewImage'])->name('admin.coupons.preview-image');
    Route::post('/coupons/print-images', [CouponController::class, 'printImages'])->name('admin.coupons.print-images');
    
    // Coupon Templates
    Route::get('/templates', [CouponTemplateController::class, 'index'])->name('admin.templates.index');
    Route::get('/templates/create', [CouponTemplateController::class, 'create'])->name('admin.templates.create');
    Route::post('/templates', [CouponTemplateController::class, 'store'])->name('admin.templates.store');
    Route::get('/templates/{template}/edit', [CouponTemplateController::class, 'edit'])->name('admin.templates.edit');
    Route::put('/templates/{template}', [CouponTemplateController::class, 'update'])->name('admin.templates.update');
    Route::delete('/templates/{template}', [CouponTemplateController::class, 'destroy'])->name('admin.templates.destroy');
    Route::patch('/templates/{template}/toggle-active', [CouponTemplateController::class, 'toggleActive'])->name('admin.templates.toggle-active');
    
    // Prize Templates
    Route::get('/prizes/templates', [PrizeTemplateController::class, 'index'])->name('admin.prizes.templates');
    Route::get('/prizes/create', [PrizeTemplateController::class, 'create'])->name('admin.prizes.create');
    Route::post('/prizes', [PrizeTemplateController::class, 'store'])->name('admin.prizes.store');
    Route::get('/prizes/{template}/edit', [PrizeTemplateController::class, 'edit'])->name('admin.prizes.edit');
    Route::put('/prizes/{template}', [PrizeTemplateController::class, 'update'])->name('admin.prizes.update');
    Route::delete('/prizes/{template}', [PrizeTemplateController::class, 'destroy'])->name('admin.prizes.destroy');
    
    // Prize Draw
    Route::get('/prizes/draw', [PrizeDrawController::class, 'index'])->name('admin.prizes.draw');
    Route::post('/prizes/draw', [PrizeDrawController::class, 'draw'])->name('admin.prizes.draw.start');
    Route::post('/prizes/winner/{winner}', [PrizeDrawController::class, 'updateWinner'])->name('admin.prizes.winner.update');
});