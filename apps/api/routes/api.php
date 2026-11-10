<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Placeholder Route api/v1
Route::get("/routing-check", function () {
  return response()->json(['status' => 'ok']);
});

Route::post("/auth/login", [AuthController::class,"login"]);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/auth/me', [AuthController::class, 'me']);
  Route::post('/auth/logout', [AuthController::class, 'logout']);
});