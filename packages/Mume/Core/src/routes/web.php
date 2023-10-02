<?php

use Illuminate\Support\Facades\Route;
use Mume\Core\Http\Controllers\Api\Auth\LoginController;

    Route::get('/core/foo', [LoginController::class, 'foo']);
