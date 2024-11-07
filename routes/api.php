<?php

use App\Infrastructure\Http\Controllers\Index;
use App\Infrastructure\Http\Controllers\ListingController;
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

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/', [Index::class, 'get']);

    Route::prefix('listing')->group(function () {
        Route::post('/', [ListingController::class, 'post']);
    });
});
