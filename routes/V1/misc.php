<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\MiscController;
use App\Http\Controllers\TestController;

// Misc Routes

Route::controller(MiscController::class)->group(function () {
    Route::get('/getCountries', 'getCountries')
        ->name('misc.countries');

    Route::get('/getBackgrounds', 'getBackgrounds')
        ->name('misc.backgrounds');

    Route::get('/getOnboardings', 'getOnboardings')
        ->name('misc.onboardings');

    Route::get('/getActivityKinds', 'getActivityKinds')
        ->name('misc.activity_kinds');

    Route::post('/getWeekDates', 'getWeekDates')
        ->name('misc.get_week_dates');
});

// Image requests
Route::controller(ImageController::class)
    ->prefix('image')
    ->middleware('auth:sanctum')->group(function () {

        Route::post('', 'upload')
            ->name('image.upload');

        Route::get('{id}', 'get')
            ->name('image.get');

        Route::delete('{id}', 'delete')
            ->name('image.delete');
});

Route::post('/test', [TestController::class, 'test'])->middleware('auth:sanctum');
Route::post('/test/deleteUser', [TestController::class, 'deleteUser']);

// No auth routes
Route::post('/isUserExists', [AuthController::class, 'isUserExists']);
Route::post('/resendSms', [AuthController::class, 'resendSms']);
