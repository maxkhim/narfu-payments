<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'/*, AdminMiddleware::class*/])->group(function () {

    Route::group(['prefix' => '/narfu/payments'], function () {
        Route::get('/', [\Narfu\Payments\Http\Controllers\PaymentsController::class, 'index'])
            ->name('narfu.payments');
    });


});

Route::middleware(['web', 'guest'])->group(function () {
    Route::group(['prefix' => '/narfu/payments-guest'], function () {
        Route::get('/', [\Narfu\Payments\Http\Controllers\PaymentsController::class, 'indexGuest'])
            ->name('narfu.payments-guest');
    });
});
