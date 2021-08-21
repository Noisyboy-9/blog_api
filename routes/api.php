<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class)->except('create', 'edit');
Route::post('categories', [CategoryController::class, 'store']);
