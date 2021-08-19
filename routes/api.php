<?php

use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class)->except('create', 'edit');
