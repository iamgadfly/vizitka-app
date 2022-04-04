<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\BusinessCardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GeocoderController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Specialist\AuthController as SpecialistAuthController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
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

// Specialist auth routes
Route::controller(SpecialistAuthController::class)
    ->prefix('specialist/auth')->group(function() {

    Route::post('signup', 'signup')
        ->name('specialist.auth.signup');

    Route::post('signin', 'signin')
        ->name('specialist.auth.signin');

    Route::post('setPin', 'setPin')
        ->middleware('auth:sanctum')
        ->name('specialist.auth.setpin');

    Route::post('verification', 'verification')
        ->name('specialist.auth.verification');

    Route::post('logout',  'logout')
        ->middleware('auth:sanctum')
        ->name('specialist.auth.logout');
});

// Client auth routes
Route::controller(ClientAuthController::class)
    ->prefix('client/auth')->group(function () {

        Route::post('signup', 'signup')
            ->name('client.auth.signup');

        Route::post('signin', 'signin')
            ->name('client.auth.signin');

        Route::post('sendPassword', 'sendPassword')
            ->name('client.auth.sendPassword');

        Route::post('verification', 'verification')
            ->name('client.auth.verification');

        Route::post('logout',  'logout')
            ->middleware('auth:sanctum')
            ->name('client.auth.logout');
});

// Business card routes
Route::controller(BusinessCardController::class)
    ->prefix('card')
    ->middleware('auth:sanctum')->group(function () {

    Route::get('{id}', 'get')
        ->name('card.get');
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

// Specialist routes
Route::controller(SpecialistController::class)
    ->prefix('specialist')
    ->middleware('auth:sanctum')->group(function () {

        Route::post('profile','create')
            ->name('specialist.create');

        Route::get('profile/{id}', 'get')
            ->name('specialist.get');

        Route::put('profile/{id}', 'update')
            ->name('specialist.update');

        Route::get('profile', 'me')
            ->name('specialist.me');
    });

// Client routes
Route::controller(ClientController::class)
    ->prefix('client')
    ->middleware('auth:sanctum')->group(function () {

    Route::post('profile', 'create')
        ->name('client.create');

    Route::get('profile/{id}', 'get')
        ->name('client.get');

    Route::get('profile', 'me')
        ->name('client.me');
});

Route::post('/geocode', [GeocoderController::class, 'geocode']);

Route::post('/test', [TestController::class, 'test']);
