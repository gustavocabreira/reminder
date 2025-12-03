<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReminderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('reminders', ReminderController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [LoginController::class, 'logout']);
});