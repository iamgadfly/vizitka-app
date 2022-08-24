<?php

use App\Http\Controllers\Api\AuthController;

Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function() {

    Route::post('signup', 'signUp')
        ->name('auth.signUp');

    Route::post('signin', 'signin')
        ->name('auth.singIn');

    Route::post('verify', 'verification')
        ->name('auth.verification');

    Route::post('logout', 'logout')
        ->middleware('auth:sanctum')
        ->name('auth.logout');

    Route::post('forget', 'forgetPin')
        ->name('auth.forget');

    Route::post('pin/set', 'setPin')
        ->name('auth.pin.set');

    Route::post('pin/unset', 'unsetPin')
        ->name('auth.pin.unset');

    Route::post('face/set', 'setFace')
        ->name('auth.face.set');

    Route::post('face/unset', 'unsetFace')
        ->name('auth.face.unset');
});
