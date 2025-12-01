<?php

declare(strict_types=1);

use App\Events\NotifyReminderEvent;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('test', function() {
    $reminder = Reminder::first();

    NotifyReminderEvent::dispatch($reminder);
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
