<?php

declare(strict_types=1);

use App\Jobs\DispatchReminderJob;
use App\Models\Reminder;
use App\Models\User;
use App\Notifications\ReminderNotification;
use Illuminate\Support\Facades\Notification;

it('should send the notification to the user', function () {
    $this->freezeTime();
    Notification::fake();

    $user = User::factory()->create();
    $reminder = Reminder::factory()->create([
        'user_id' => $user->id,
        'scheduled_at' => now()->addMinutes(5),
    ]);

    $this->assertDatabaseHas('reminders', [
        'id' => $reminder->id,
        'is_notified' => false,
    ]);

    $job = new DispatchReminderJob($reminder);
    $job->handle();

    $this->assertDatabaseHas('reminders', [
        'id' => $reminder->id,
        'is_notified' => 1,
    ]);

    Notification::assertSentTo($user, ReminderNotification::class, function (ReminderNotification $notification) use ($reminder) {
        return $notification->reminder->id === $reminder->id;
    });
});
