<?php

use Illuminate\Support\Facades\Route;

// Placeholder Route api/v1
Route::get("/routing-check", function () {
  return response()->json(['status' => 'ok']);
});