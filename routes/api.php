<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SampahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * @group User Management
 * API untuk mengelola data User
 */
Route::apiResource('users', UserController::class);

/**
 * @group Sampah Management
 * API untuk mengelola data Sampah dengan sort by id dan jenis_sampah (10 per page)
 */
Route::apiResource('sampah', SampahController::class);

/**
 * Health Check
 * @authenticated
 */
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now()
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
