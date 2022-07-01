<?php

use App\Http\Controllers\Api\ShareController;
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
