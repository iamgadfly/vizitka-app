<?php

use App\Http\Controllers\Api\Client\AppointmentController;
use App\Http\Controllers\Api\Client\ContactBookController;
use App\Http\Controllers\Api\Client\DummyBusinessCardController;
use App\Http\Controllers\Api\Client\ReportController;
use App\Http\Controllers\Api\Client\SpecialistDataController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\Specialist\SupportController;

// Client routes

Route::controller(ClientController::class)
    ->prefix('client')
    ->middleware('auth:sanctum')->group(function () {

        Route::post('profile', 'create')
            ->name('client.create');

        Route::get('profile/{id}', 'get')
            ->name('client.get');

        Route::put('profile', 'update')
            ->name('client.update');

        Route::get('profile', 'me')
            ->name('client.me');
    });

// Contact book routes

Route::controller(ContactBookController::class)
    ->prefix('client/contactBook')
    ->middleware('auth:sanctum')->group(function() {

        Route::post('/mass', 'massCreate')
            ->name('client.contactBook.create.mass');

        Route::post('{id}', 'create')
            ->name('client.contactBook.create');

        Route::delete('{type}/{id}', 'delete')
            ->name('client.contactBook.delete');

        Route::get('', 'get')
            ->name('client.contactBook.get');
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

Route::controller(SpecialistDataController::class)
    ->prefix('client/specialist')
    ->middleware('auth:sanctum')->group(function () {

    Route::get('{id}/freeHours/{sum}/{date}/{hour}', 'getFreeHours')
        ->name('specialistData.freeHours');

    Route::get('{id}/maintenances', 'getMaintenances')
        ->name('specialistData.maintenances');
});

// Appointment routes
Route::controller(AppointmentController::class)
    ->prefix('client/appointment')
    ->middleware('auth:sanctum')->group(function () {

    Route::post('specialist/{id}', 'create')
        ->middleware('blacklisted')
        ->name('client.appointment.create');

    Route::put('specialist/{orderNumber}', 'update')
        ->name('client.appointment.update');

    Route::get('history', 'getMyHistory')
        ->name('client.appointment.history');

    Route::get('specialist/{id}/history', 'getHistoryForSpecialist')
        ->name('client.appointment.specialist.history');

    Route::post('specialist/{id}/checkForDuplicates', 'checkForDuplicates')
        ->name('client.appointment.duplicates');
});

// Report routes

Route::controller(ReportController::class)
    ->prefix('client/report')
    ->middleware('auth:sanctum')->group(function () {

    Route::post('{id}', 'createReport')
        ->name('client.report.create');

    Route::get('reasons', 'getReportReasons')
        ->name('client.report.reasons');
});

// Report Routes
Route::controller(SupportController::class)
    ->prefix('client/support')
    ->middleware('auth:sanctum')->group(function () {

        Route::post('', 'createSupportAsClient')
            ->name('client.support.create');
    });

