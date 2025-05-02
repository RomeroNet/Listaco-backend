<?php

use App\Listing\Infrastructure\Http\Controller\ListingController;
use App\Listing\Infrastructure\Http\Controller\Uuid\ListingByUuidController;
use Illuminate\Support\Facades\Route;

Route::post('/', [ListingController::class, 'post']);

Route::prefix('/{uuid}')->group(function () {
    Route::get('/', [ListingByUuidController::class, 'get']);
    Route::delete('/', [ListingByUuidController::class, 'delete']);
    Route::patch('/', [ListingByUuidController::class, 'patch']);
});
