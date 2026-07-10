<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\Pengantin\DashboardController as PengantinDashboard;
use App\Http\Controllers\Pengantin\GuestController;
use App\Http\Controllers\Pengantin\GuestGroupController;
use App\Http\Controllers\Pengantin\ScannerManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\PricingPackageController;
use App\Http\Controllers\Admin\PricingFeatureController;
use App\Http\Controllers\Admin\OrderManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $packages = \App\Models\PricingPackage::with('features')
        ->active()
        ->ordered()
        ->get();
    return view('welcome', compact('packages'));
})->name('home');

// Public invitation & RSVP
Route::get('/invitation/{slug}', [PublicInvitationController::class, 'show'])->name('invitation.show');
Route::post('/invitation/{slug}/rsvp', [PublicInvitationController::class, 'rsvp'])->name('invitation.rsvp');
Route::get('/invitation/{slug}/ticket', [PublicInvitationController::class, 'ticket'])->name('invitation.ticket');
Route::post('/wishes/{eventSlug}', [PublicInvitationController::class, 'storeWish'])->name('wishes.store');

/*
|--------------------------------------------------------------------------
| Public Order & Payment Routes
|--------------------------------------------------------------------------
*/
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/{packageSlug}/checkout', [OrderController::class, 'create'])->name('create');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('show');
    Route::get('/{orderNumber}/payment', [OrderController::class, 'payment'])->name('payment');
    Route::post('/{orderNumber}/payment', [OrderController::class, 'processPayment'])->name('process-payment');
});

// Payment Gateway Webhooks (no CSRF)
Route::prefix('payment/callback')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    Route::post('/midtrans', [PaymentCallbackController::class, 'handleMidtrans'])->name('payment.callback.midtrans');
    Route::post('/xendit', [PaymentCallbackController::class, 'handleXendit'])->name('payment.callback.xendit');
});

/*
|--------------------------------------------------------------------------
| Auth redirect dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match($user->role->name) {
        'superadmin' => redirect()->route('admin.dashboard'),
        'pengantin' => redirect()->route('pengantin.dashboard'),
        'petugas_scan' => redirect()->route('scanner.index'),
        default => redirect()->route('home'),
    };
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Pengantin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pengantin,superadmin', 'log.activity', 'managed.event'])
    ->prefix('pengantin')
    ->name('pengantin.')
    ->group(function () {
        Route::get('/dashboard', [PengantinDashboard::class, 'index'])->name('dashboard');

        // Guests
        Route::resource('guests', GuestController::class);
        Route::post('/guests/{guest}/generate-qr', [GuestController::class, 'generateQr'])->name('guests.generate-qr');
        Route::post('/guests/bulk-generate-qr', [GuestController::class, 'bulkGenerateQr'])->name('guests.bulk-generate-qr');
        Route::post('/guests/bulk-destroy', [GuestController::class, 'bulkDestroy'])->name('guests.bulk-destroy');

        // Guest Groups
        Route::resource('groups', GuestGroupController::class)->except(['create', 'show', 'edit']);

        // Scanner Management
        Route::resource('scanners', ScannerManagementController::class)->except(['show']);
        Route::post('/scanners/{scanner}/toggle-active', [ScannerManagementController::class, 'toggleActive'])->name('scanners.toggle-active');
        Route::post('/scanners/{scanner}/reset-password', [ScannerManagementController::class, 'resetPassword'])->name('scanners.reset-password');
    });

/*
|--------------------------------------------------------------------------
| Scanner Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas_scan,superadmin'])
    ->prefix('scanner')
    ->name('scanner.')
    ->group(function () {
        Route::get('/', [ScannerController::class, 'index'])->name('index');
        Route::post('/scan', [ScannerController::class, 'scan'])->name('scan');
        Route::get('/search', [ScannerController::class, 'manualSearch'])->name('search');
        Route::post('/manual-checkin', [ScannerController::class, 'manualCheckin'])->name('manual-checkin');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin', 'log.activity'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Users
        Route::resource('users', AdminUserController::class);
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');

        // Client Management (user-centric: driven by pengantin accounts)
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/{user}/setup', [ClientController::class, 'setup'])->name('clients.setup');
        Route::post('/clients/{user}/setup', [ClientController::class, 'storeSetup'])->name('clients.store-setup');
        Route::get('/clients/{user}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('/clients/{user}', [ClientController::class, 'update'])->name('clients.update');
        Route::post('/clients/{user}/toggle', [ClientController::class, 'toggleActive'])->name('clients.toggle');
        Route::delete('/clients/{user}', [ClientController::class, 'destroy'])->name('clients.destroy');
        Route::post('/clients/{event}/manage', [ClientController::class, 'manage'])->name('clients.manage');
        Route::post('/clients/switch-back', [ClientController::class, 'switchBack'])->name('clients.switch-back');

        // Pricing Package Management
        Route::resource('pricing', PricingPackageController::class)->parameters(['pricing' => 'package']);
        Route::post('/pricing/{package}/toggle-active', [PricingPackageController::class, 'toggleActive'])->name('pricing.toggle-active');
        Route::post('/pricing/update-order', [PricingPackageController::class, 'updateOrder'])->name('pricing.update-order');

        // Pricing Features Management
        Route::post('/pricing/{package}/features', [PricingFeatureController::class, 'store'])->name('pricing.features.store');
        Route::put('/pricing/features/{feature}', [PricingFeatureController::class, 'update'])->name('pricing.features.update');
        Route::delete('/pricing/features/{feature}', [PricingFeatureController::class, 'destroy'])->name('pricing.features.destroy');
        Route::post('/pricing/{package}/features/update-order', [PricingFeatureController::class, 'updateOrder'])->name('pricing.features.update-order');

        // Order Management
        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.update-status');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
