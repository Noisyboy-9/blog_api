<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Post\PostController;

//posts
Route::resource('posts', PostController::class)->except('create', 'edit');

//categories
Route::post('categories', [CategoryController::class, 'store']);

// authentication
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);
