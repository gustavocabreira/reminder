<?php

declare(strict_types=1);

use App\Events\NotifyReminderEvent;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {

    $user = User::first();

    if (! $user) {
        $user = User::factory()->create();
    }

    $reminder = Reminder::first();

    if (! $reminder) {
        $reminder = Reminder::factory()->create([
            'user_id' => $user->id,
        ]);
    }

    NotifyReminderEvent::dispatch($reminder);
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!docs/api).*$');
