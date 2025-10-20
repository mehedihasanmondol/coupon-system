<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PrizeTemplateController;
use App\Http\Controllers\PrizeDrawController;

// Public Routes
Route::get('/', function () {
    return redirect('/login');
});

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
    Route::get('/coupons', [CouponController::class, 'index'])->name('admin.coupons');
    Route::post('/coupons/generate', [CouponController::class, 'generate'])->name('admin.coupons.generate');
    
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