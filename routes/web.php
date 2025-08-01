<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'API de seguimiento trading v1.0'
    ]);
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function (string $token) {
    return response()->json([
        'message' => 'Use this token in your mobile app',
        'token' => $token
    ]);
})->name('password.reset');

// Ruta requerida por Laravel al fallar autenticación
Route::get('/login', function () {
    return response()->json([
        'message' => 'No autenticado. Esta API no tiene frontend.'
    ], 401);
})->name('login');
