<?php

use App\Infrastructure\Http\Controllers\Item\ItemController;

Route::post('/', [ItemController::class, 'post']);
