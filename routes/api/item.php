<?php

use App\Item\Infrastructure\Http\Controllers\ItemController;

Route::post('/', [ItemController::class, 'post']);
