<?php

use App\Http\Controllers\Api\OrderImportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders/import', [OrderImportController::class, 'store']);
});
