<?php

use App\Infrastructure\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::post('/', [ListingController::class, 'post']);

Route::prefix('/{uuid}')->group(function () {
    Route::get('/', [ListingController::class, 'getByUuid']);
    Route::delete('/', [ListingController::class, 'deleteByUuid']);
    Route::patch('/', [ListingController::class, 'patch']);
});
