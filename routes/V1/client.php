<?php

use App\Http\Controllers\Api\Client\DummyBusinessCardController;
use App\Http\Controllers\Api\ClientController;

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
