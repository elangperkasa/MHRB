<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/addlog2', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('/addlog', LoginController::class); ini OK

Route::get('addlog', [LoginController::class, 'index']);
