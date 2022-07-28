<?php

use App\Http\Controllers\Api\ShareController;
use App\Http\Controllers\DeepLinkingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Share routes

Route::get('/shares/{hash}', [ShareController::class, 'get'])
    ->name('share.link');


Route::get('/.well-known/assetlinks.json', [DeepLinkingController::class, 'android']);
Route::get('/apple-app-site-association', [DeepLinkingController::class, 'apple']);
