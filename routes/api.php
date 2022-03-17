<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SpecialistController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', [AuthController::class, 'signup'])
        ->name('auth.signup');

    Route::post('signin', [AuthController::class, 'signin'])
        ->name('auth.signin');

    Route::post('sendVerificationCode', [AuthController::class, 'sendVerificationCode'])
        ->name('auth.sendVerificationCode');

    Route::post('verification', [AuthController::class, 'verification'])
        ->name('auth.verification');

    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('auth.logout');
});

Route::group(['prefix' => 'specialist', 'middleware' => 'auth:sanctum'], function () {
    Route::post('profile', [SpecialistController::class, 'create'])
        ->name('specialist.create');
    Route::get('profile/{id}', [SpecialistController::class, 'get'])
        ->name('specialist.get');
});
Route::group(['prefix' => 'client', 'middleware' => 'auth:sanctum'], function () {
    Route::post('profile', [ClientController::class, 'create'])
        ->name('client.create');
    Route::get('profile/{id}', [ClientController::class, 'get'])
        ->name('client.get');
});
