<?php

use Illuminate\Support\Facades\Route;
use Mume\Product\Http\Controllers\Api\ProductController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(
        ['prefix' => 'products'],
        function () {
            Route::get('/', [ProductController::class, 'list']);
            Route::post('/', [ProductController::class, 'add'])->name('api.product.add');
            Route::get('/{id}', [ProductController::class, 'detail'])->name('api.product.detail');
            Route::put('/{id}', [ProductController::class, 'update'])->name('api.product.update');
            Route::delete('/', [ProductController::class, 'delete'])->name('api.product.delete');
        }
    );
});
