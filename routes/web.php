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

$app_url = config("app.url");
if (app()->environment('local') && !empty($app_url)) {
    URL::forceRootUrl($app_url);
    $schema = explode(':', $app_url)[0];
    URL::forceScheme($schema);
}


Route::get('/shares/{hash}', [ShareController::class, 'get'])
    ->name('share.link');


Route::get('/.well-known/assetlinks.json', [DeepLinkingController::class, 'android']);
Route::get('/apple-app-site-association', [DeepLinkingController::class, 'apple']);
