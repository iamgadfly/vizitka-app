<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Api\Client\DummyBusinessCardController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\MiscController;
use App\Http\Controllers\Api\Specialist\AuthController as SpecialistAuthController;
use App\Http\Controllers\Api\Specialist\BusinessCardController;
use App\Http\Controllers\Api\Specialist\MaintenanceController;
use App\Http\Controllers\Api\Specialist\WorkScheduleController;
use App\Http\Controllers\Api\SpecialistController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DummyClientController;
use App\Http\Controllers\TestController;
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

// Maintenance routes
Route::controller(MaintenanceController::class)
    ->middleware('auth:sanctum')
    ->prefix('specialist/maintenance')->group(function () {

    Route::post('', 'create')
        ->name('specialist.maintenance.create');

    Route::get('', 'get')
        ->name('specialist.maintenance.get');

    Route::put('/settings', 'updateSettings')
        ->name('specialist.maintenance.settings.update');

    Route::put('{id}', 'update')
        ->name('specialist.maintenance.update');

    Route::delete('{id}', 'delete')
        ->name('specialist.maintenance.delete');
});

// Work schedule routes
Route::controller(WorkScheduleController::class)
    ->middleware('auth:sanctum')
    ->prefix('specialist/schedule')->group(function() {

    Route::get('', 'get')
        ->name('specialist.schedule.get');
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

// Business card routes
Route::controller(BusinessCardController::class)
    ->prefix('card')
    ->middleware('auth:sanctum')->group(function () {

    Route::get('{id}', 'get')
        ->name('card.get');

    Route::put('{id}', 'update')
        ->name('card.update');
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

        Route::put('profile/{id}', 'update')
            ->name('client.update');

    Route::get('profile', 'me')
        ->name('client.me');
});

//Dummy business card routes
Route::controller(DummyBusinessCardController::class)
    ->prefix('client/card')
    ->middleware('auth:sanctum')->group(function() {

    Route::get('{id}', 'get')
        ->name('client.card.get');

    Route::post('', 'create')
        ->name('client.card.create');

    Route::put('{id}', 'update')
        ->name('client.card.update');

    Route::delete('{id}', 'delete')
        ->name('client.card.delete');
});

// Dummy client routes
Route::controller(DummyClientController::class)
    ->prefix('specialist/client')
    ->middleware('auth:sanctum')->group(function() {

    Route::post('', 'create')
        ->name('specialist.client.create');

    Route::get('{id}', 'get')
        ->name('specialist.client.get');

    Route::put('{id}', 'update')
        ->name('specialist.client.update');

    Route::delete('{id}', 'delete')
        ->name('specialist.client.delete');
});

// Appointment routes
Route::controller(AppointmentController::class)
    ->prefix('specialist/appointment')
    ->middleware('auth:sanctum')->group(function() {

    Route::post('', 'create')
        ->name('specialist.appointment.create');

    Route::get('{id}', 'get')
        ->name('specialist.appointment.get');

    Route::put('{id}', 'update')
        ->name('specialist.appointment.update');

    Route::delete('{id}', 'delete')
        ->name('specialist.appointment.delete');

    Route::post('{id}/confirm', 'confirm')
        ->name('specialist.appointment.confirm');

    Route::post('{id}/skipped', 'skipped')
        ->name('specialist.appointment.skipped');
});

// Misc Routes

Route::controller(MiscController::class)->group(function () {
    Route::get('/getCountries', 'getCountries')
        ->name('misc.countries');

    Route::get('/getBackgrounds', 'getBackgrounds')
        ->name('misc.backgrounds');

    Route::get('/getOnboardings', 'getOnboardings')
        ->name('misc.onboardings');

    Route::get('/getActivityKinds', 'getActivityKinds')
        ->name('name.activity_kinds');
});

Route::post('/test', [TestController::class, 'test']);

Route::post('/isUserExists', [AuthController::class, 'isUserExists']);
Route::post('/resendSms', [AuthController::class, 'resendSms']);
