<?php

declare(strict_types=1);

use App\Http\Controllers\Api\HuggyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReminderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('reminders', ReminderController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', function (Request $request) {
        return $request->user()->toResource();
    });
   
    Route::post('logout', [LoginController::class, 'logout']);

    Route::prefix('huggy')->group(function () {
        Route::get('contacts', [HuggyController::class, 'getContacts']);
        Route::get('chats', [HuggyController::class, 'getChats']);
    });
});