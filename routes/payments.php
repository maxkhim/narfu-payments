<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'/*, AdminMiddleware::class*/, 'throttle:60,1'])->group(function () {

    Route::group(['prefix' => '/narfu/payments'], function () {
        Route::get('/', [\Narfu\Payments\Http\Controllers\PaymentsController::class, 'index'])
            ->name('narfu.payments');
    });


});

Route::middleware(['web', 'guest', 'throttle:60,1'])->group(function () {
    Route::group(['prefix' => '/narfu/payments-guest'], function () {
        Route::get('/', [\Narfu\Payments\Http\Controllers\PaymentsController::class, 'indexGuest'])
            ->name('narfu.payments-guest');
    });
});
