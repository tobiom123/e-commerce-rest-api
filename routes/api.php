<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('items', \App\Http\Controllers\ItemController::class);
    Route::apiResource('orders', \App\Http\Controllers\OrderController::class);
});
