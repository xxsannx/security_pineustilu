<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

// Endpoint webhook Midtrans (stateless - tanpa session / cookie)
Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])
    ->name('api.payment.notification');
