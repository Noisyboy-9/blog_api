<?php

use App\Http\Controllers\PostController;

Route::post('/posts', [PostController::class, 'store']);
