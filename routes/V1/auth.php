<?php

use App\Http\Controllers\Api\Specialist\AuthController as SpecialistAuthController;
use App\Http\Controllers\Api\Client\AuthController as ClientAuthController;

// Specialist auth routes
Route::controller(SpecialistAuthController::class)
    ->prefix('specialist/auth')->group(function() {

        Route::post('signup', 'signup')
            ->name('specialist.auth.signup');

        Route::post('signin', 'signIn')
            ->name('specialist.auth.signIn');

        Route::post('setPin', 'setPin')
            ->middleware('auth:sanctum')
            ->name('specialist.auth.setpin');

        Route::post('verification', 'verification')
            ->name('specialist.auth.verification');

        Route::post('logout',  'logout')
            ->middleware('auth:sanctum')
            ->name('specialist.auth.logout');

        Route::post('sendPinResetRequest', 'sendPinResetRequest')
            ->name('specialist.auth.send-pin-reset-request');

        Route::post('pinReset', 'pinReset')
            ->name('specialist.auth.pin-reset');
});

// Client auth routes
Route::controller(ClientAuthController::class)
    ->prefix('client/auth')->group(function () {

        Route::post('signup', 'signup')
            ->name('client.auth.signup');

        Route::post('signin', 'signIn')
            ->name('client.auth.signIn');

        Route::post('sendPassword', 'sendPassword')
            ->name('client.auth.sendPassword');

        Route::post('verification', 'verification')
            ->name('client.auth.verification');

        Route::post('logout',  'logout')
            ->middleware('auth:sanctum')
            ->name('client.auth.logout');
});
