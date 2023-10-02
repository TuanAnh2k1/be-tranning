<?php

use Illuminate\Support\Facades\Route;
use Mume\Core\Http\Controllers\Api\Auth\LoginController;
use Mume\Core\Http\Controllers\Api\Role\RoleController;
use Mume\Core\Http\Controllers\Api\User\UserController;

Route::post('/login', [LoginController::class, 'login'])->name('api.login');
Route::middleware('auth:sanctum')->group(function () {
    Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('api.logout');
    Route::match(['get'], '/roles', [RoleController::class, 'list'])->name('api.role.list');

    // User route
    Route::group(
        ['prefix' => 'users'],
        function () {
            Route::get('/', [UserController::class, 'list'])->name('api.user.list');
            Route::get('/me', [UserController::class, 'me'])->name('api.user.me');
            Route::post('/', [UserController::class, 'add'])->name('api.user.add');
            Route::get('/{id}', [UserController::class, 'detail'])->name('api.user.detail');
            Route::put('/{id}', [UserController::class, 'update'])->name('api.user.update');
            Route::put('/{id}/password', [UserController::class, 'updatePassword'])->name('api.user.update_password');
            Route::delete('/', [UserController::class, 'delete'])->name('api.user.delete');
        }
    );
});
