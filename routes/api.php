<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Myfxbook\AccountController;
use App\Http\Controllers\Myfxbook\DailySummaryController;
use App\Http\Controllers\Myfxbook\TradeController;
use Illuminate\Support\Facades\Route;


// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);



// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::apiResource('accounts', AccountController::class);
});

// Ruta genérica para endpoints inexistentes
Route::fallback(function () {
    return response()->json([
        'message' => 'Ruta de API no válida. Verifica la URL o método.'
    ], 404);
});

Route::get('/test', function () {
    return response()->json(['message' => 'Conexión OK desde MT4']);
});

Route::apiResource('trades', TradeController::class);
Route::apiResource('summaries', DailySummaryController::class);


