<?php

use App\Http\Controllers\Api\ShareController;
use App\Http\Controllers\Api\Specialist\AppointmentController;
use App\Http\Controllers\Api\Specialist\BlacklistController;
use App\Http\Controllers\Api\Specialist\BusinessCardController;
use App\Http\Controllers\Api\Specialist\ClientController;
use App\Http\Controllers\Api\Specialist\ContactBookController;
use App\Http\Controllers\Api\Specialist\DummyClientController;
use App\Http\Controllers\Api\Specialist\MaintenanceController;
use App\Http\Controllers\Api\Specialist\SingleWorkScheduleController;
use App\Http\Controllers\Api\Specialist\WorkScheduleController;
use App\Http\Controllers\Api\SpecialistController;

// Maintenance routes

Route::controller(MaintenanceController::class)
    ->middleware('auth:sanctum')
    ->prefix('specialist/maintenance')->group(function () {

        Route::post('', 'store')
            ->name('specialist.maintenance.create');

        Route::post('/new', 'create')
            ->name('specialist.maintenance.new');

        Route::get('', 'get')
            ->name('specialist.maintenance.get');

        Route::put('/settings', 'updateSettings')
            ->name('specialist.maintenance.settings.update');

        Route::put('{id}', 'update')
            ->name('specialist.maintenance.update');

        Route::delete('{id}', 'delete')
            ->name('specialist.maintenance.delete');

        Route::get('all', 'all')
            ->name('specialist.maintenance.all');
    });

// Work schedule routes
Route::controller(WorkScheduleController::class)
    ->middleware('auth:sanctum')
    ->prefix('specialist/schedule')->group(function() {

        Route::post('', 'create')
            ->name('specialist.schedule.create');

        Route::get('', 'get')
            ->name('specialist.schedule.get');

        Route::put('', 'update')
            ->name('specialist.schedule.update');
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

// Specialist routes
Route::controller(SpecialistController::class)
    ->prefix('specialist')
    ->middleware('auth:sanctum')->group(function () {

        Route::post('profile','create')
            ->name('specialist.create');

        Route::get('profile/{id}', 'get')
            ->name('specialist.get');

        Route::put('profile', 'update')
            ->name('specialist.update');

        Route::get('profile', 'me')
            ->name('specialist.me');

        Route::get('card', 'getMyCard')
            ->name('specialist.card');
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

        Route::get('', 'all')
            ->name('specialist.client.all');
    });

// Appointment routes
Route::controller(AppointmentController::class)
    ->prefix('specialist/appointment')
    ->middleware('auth:sanctum')->group(function() {

        Route::post('', 'create')
            ->name('specialist.appointment.create');

        Route::get('{orderNumber}', 'get')
            ->name('specialist.appointment.get');

        Route::put('{orderNumber}', 'update')
            ->name('specialist.appointment.update');

        Route::delete('{orderNumber}', 'delete')
            ->name('specialist.appointment.delete');

        Route::post('{orderNumber}/confirm', 'confirm')
            ->name('specialist.appointment.confirm');

        Route::post('{orderNumber}/skipped', 'skipped')
            ->name('specialist.appointment.skipped');

        Route::post('byDay', 'getAllByDay')
            ->name('specialist.appointment.byDay');

        Route::post('svgByMonth', 'svgByMonth')
            ->name('specialist.appointment.svg');

        Route::delete('mass', 'massDelete')
            ->name('specialist.appointment.delete.mass');
    });

// Single work schedule routes

Route::controller(SingleWorkScheduleController::class)
    ->prefix('specialist/schedule/single')
    ->middleware('auth:sanctum')->group(function() {

        Route::post('', 'create')
            ->name('specialist.schedule.single.create');

        Route::post('break', 'createBreak')
            ->name('specialist.schedule.single.create.break');

        Route::post('workday', 'createWorkday')
            ->name('specialist.schedule.single.create.workday');

        Route::delete('{id}', 'delete')
            ->name('specialist.schedule.single.delete');
    });

// Blacklist routes

Route::controller(BlacklistController::class)
    ->prefix('specialist/blacklist')
    ->middleware('auth:sanctum')->group(function() {

        Route::post('{id}', 'create')
            ->name('blacklist.add');

        Route::delete('{id}/{type}', 'delete')
            ->name('blacklist.delete');

        Route::get('', 'get')
            ->name('blacklist.get');
    });

// Contact book routes

Route::controller(ContactBookController::class)
    ->prefix('specialist/contactBook')
    ->middleware('auth:sanctum')->group(function() {

        Route::post('/mass', 'massCreate')
            ->name('specialist.contactBook.create.mass');

        Route::post('{id}', 'create')
            ->name('specialist.contactBook.create');

        Route::delete('{id}/{type}', 'delete')
            ->name('specialist.contactBook.delete');

        Route::get('', 'get')
            ->name('specialist.contactBook.get');
    });

// Client Data routes
Route::controller(ClientController::class)
    ->prefix('specialist/clientData')
    ->middleware('auth:sanctum')->group(function() {

        Route::get('{id}/{type}/history', 'getClientHistory')
            ->name('specialist.client.data.history');
    });


// Share routes
Route::controller(ShareController::class)
    ->prefix('share')
    ->middleware('auth:sanctum')->group(function () {

        Route::post('qrcode', 'getQrCode')
            ->name('specialist.share.qrcode');
    });
