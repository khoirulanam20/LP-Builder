<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'is.approved'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-lp', [\App\Http\Controllers\MyLPController::class, 'index'])->name('my-lp.index');
    Route::post('/my-lp', [\App\Http\Controllers\MyLPController::class, 'update'])->name('my-lp.update');
    Route::post('/my-lp/product', [\App\Http\Controllers\MyLPController::class, 'storeProduct'])->name('my-lp.product.store');
    Route::delete('/my-lp/product/{id}', [\App\Http\Controllers\MyLPController::class, 'destroyProduct'])->name('my-lp.product.destroy');
    Route::post('/my-lp/addon', [\App\Http\Controllers\MyLPController::class, 'storeAddOn'])->name('my-lp.addon.store');
    Route::delete('/my-lp/addon/{id}', [\App\Http\Controllers\MyLPController::class, 'destroyAddOn'])->name('my-lp.addon.destroy');
    Route::get('/appearance', [\App\Http\Controllers\AppearanceController::class, 'index'])->name('appearance.index');
    Route::post('/appearance', [\App\Http\Controllers\AppearanceController::class, 'update'])->name('appearance.update');
    Route::get('/statistic', [\App\Http\Controllers\StatisticController::class, 'index'])->name('statistic.index');
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/verify', [\App\Http\Controllers\OrderController::class, 'verify'])->name('orders.verify');
    Route::post('/orders/{id}/sync', [\App\Http\Controllers\OrderController::class, 'syncStatus'])->name('orders.sync');
    Route::get('/setting', [\App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting/payment', [\App\Http\Controllers\SettingController::class, 'storePayment'])->name('setting.payment.store');
    Route::delete('/setting/payment/{id}', [\App\Http\Controllers\SettingController::class, 'destroyPayment'])->name('setting.payment.destroy');
    Route::post('/setting/email', [\App\Http\Controllers\SettingController::class, 'updateEmail'])->name('setting.email.update');
    
    Route::get('/vouchers', [\App\Http\Controllers\VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers', [\App\Http\Controllers\VoucherController::class, 'store'])->name('vouchers.store');
    Route::delete('/vouchers/{id}', [\App\Http\Controllers\VoucherController::class, 'destroy'])->name('vouchers.destroy');
});

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/users', [\App\Http\Controllers\SuperadminController::class, 'usersIndex'])->name('superadmin.users.index');
    Route::get('/users/{id}', [\App\Http\Controllers\SuperadminController::class, 'userShow'])->name('superadmin.users.show');
    Route::post('/users/{id}/approve', [\App\Http\Controllers\SuperadminController::class, 'approveUser'])->name('superadmin.users.approve');
    Route::post('/settings', [\App\Http\Controllers\SuperadminController::class, 'updateSettings'])->name('superadmin.settings.update');
    Route::post('/site-profile', [\App\Http\Controllers\SuperadminController::class, 'updateSiteProfile'])->name('superadmin.site-profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Midtrans webhook — no CSRF
Route::post('/midtrans/notification', [\App\Http\Controllers\PublicPageController::class, 'midtransNotification'])
    ->name('midtrans.notification')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';

// Public Facing Routes (Must be at the very bottom to avoid route conflicts)
Route::get('/{slug}', [\App\Http\Controllers\PublicPageController::class, 'show'])->name('public.show');
Route::get('/{slug}/p/{id}', [\App\Http\Controllers\PublicPageController::class, 'productDetail'])->name('public.product');
Route::get('/{slug}/checkout', [\App\Http\Controllers\PublicPageController::class, 'checkout'])->name('public.checkout');
Route::post('/{slug}/checkout', [\App\Http\Controllers\PublicPageController::class, 'processCheckout'])->name('public.checkout.process');
Route::post('/{slug}/apply-voucher', [\App\Http\Controllers\PublicPageController::class, 'applyVoucher'])->name('public.applyVoucher');
Route::get('/{slug}/success', [\App\Http\Controllers\PublicPageController::class, 'success'])->name('public.success');
Route::post('/{slug}/review', [\App\Http\Controllers\PublicPageController::class, 'submitReview'])->name('public.review.submit');
