<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController; // Kita akan buat ini nanti

/*
|--------------------------------------------------------------------------
| AREA PUBLIK (Pelanggan Tidak Perlu Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::post('/cart/{id}', [OrderController::class, 'addToCart'])->name('cart.add');
Route::get('/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
// ... route cart.add dan checkout yang sudah ada ...

// RUTE BARU UNTUK CRUD CART
Route::patch('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove/{id}', [OrderController::class, 'removeFromCart'])->name('cart.remove');

// ... route order.store ...

/*
|--------------------------------------------------------------------------
| AREA ADMIN (Wajib Login)
|--------------------------------------------------------------------------
*/
// Semua URL yang diawali /admin akan dicek loginnya


Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // === TAMBAHAN BARU ===
    Route::get('/products/create', [AdminController::class, 'create'])->name('admin.products.create');
    Route::post('/products/store', [AdminController::class, 'store'])->name('admin.products.store');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products.index');
    Route::get('/products/{id}/edit', [AdminController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroy'])->name('admin.products.destroy');
    Route::patch('/orders/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.status');
});

// routes/web.php
// routes/web.php – ganti seluruh route /test-wa
Route::get('/test-wa', function () {
    $token = env('FONNTE_API_KEY');  // Token device connected
    $phoneDigits = '85719652581';  // Nomor WA tujuan TANPA 62/0/+ (ganti dengan digit Indo kamu)

    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => $token,  // Header Fonnte: TOKEN langsung, TANPA Bearer
        'Content-Type' => 'application/x-www-form-urlencoded',
    ])->asForm()  // Form data seperti docs
    ->post('https://api.fonnte.com/send', [
        'target'      => $phoneDigits,  // Digit murni: 85719652581
        'message'     => '🎉 FONNTE + LARAVEL SUDAH JALAN 100%!\n\n' .
                         'Test berhasil dari Laravel.\n' .
                         'Waktu: ' . now()->format('d M Y H:i:s') . '\n\n' .
                         'Sekarang submission challenge bakal kirim WA otomatis ke curator!',
        'countryCode' => '62',  // Wajib untuk Indo
    ]);

    // Debug lengkap
    return "<pre style='background:#000; color:lime; padding:20px; font-size:16px; border-radius:10px;'>
<strong>STATUS CODE:</strong> {$response->status()}

<strong>RESPONSE:</strong>
{$response->body()}

<strong>TOKEN YANG DIPAKAI:</strong> {$token}

<strong>NOMOR TARGET (digit Indo):</strong> {$phoneDigits}

<strong>HEADER YANG DIKIRIM:</strong> Authorization: {$token}

Kalau status 200 dan response {\"status\":true,\"detail\":\"success\"}, WA masuk dalam 3-5 detik!
</pre>";
});

// Load rute bawaan Breeze (Login/Logout)
require __DIR__.'/auth.php';
