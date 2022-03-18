<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth routes
Route::controller(AuthController::class)
    ->prefix('auth')->group(function() {

    Route::post('signup', 'signup')
        ->name('auth.signup');

    Route::post('signin', 'signin')
        ->name('auth.signin');

    Route::post('sendVerificationCode', 'sendVerificationCode')
        ->name('auth.sendVerificationCode');

    Route::post('verification', 'verification')
        ->name('auth.verification');

    Route::post('logout',  'logout')
        ->middleware('auth:sanctum')
        ->name('auth.logout');
});

// Specialist routes
Route::controller(SpecialistController::class)
    ->prefix('specialist')
    ->middleware('auth:sanctum')->group(function () {

    Route::post('profile','create')
        ->name('specialist.create');

    Route::get('profile/{id}', 'get')
        ->name('specialist.get');
});

// Client routes
Route::controller(ClientController::class)
    ->prefix('client')
    ->middleware('auth:sanctum')->group(function () {

    Route::post('profile', [ClientController::class, 'create'])
        ->name('client.create');

    Route::get('profile/{id}', [ClientController::class, 'get'])
        ->name('client.get');
});

Route::post('/test', [TestController::class, 'test']);
