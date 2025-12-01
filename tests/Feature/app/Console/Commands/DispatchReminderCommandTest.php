<?php

declare(strict_types=1);

use App\Jobs\DispatchReminderJob;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

it('should dispatch the available reminders 10 minutes before the scheduled time', function () {
    $this->freezeTime();
    Queue::fake();

    $user = User::factory()->create();
    $reminder = Reminder::factory()->create([
        'user_id' => $user->id,
        'scheduled_at' => now()->addMinutes(10),
    ]);

    $this->artisan('app:dispatch-reminder-command');

    Queue::assertPushed(DispatchReminderJob::class, function (DispatchReminderJob $job) use ($reminder) {
        return $job->reminder->id === $reminder->id;
    });
});

it('should not dispatch the reminders that are already notified', function () {
    Queue::fake();

    $user = User::factory()->create();
    Reminder::factory()->count(1)->create([
        'user_id' => $user->id,
        'is_notified' => true,
    ]);

    $this->artisan('app:dispatch-reminder-command');

    Queue::assertNotPushed(DispatchReminderJob::class);
});

it('should not dispatch the reminders if the scheduled time is more than 10 minutes in the future', function () {
    $this->freezeTime();
    Queue::fake();

    $user = User::factory()->create();
    Reminder::factory()->create([
        'user_id' => $user->id,
        'scheduled_at' => now()->addMinutes(20),
    ]);

    $this->artisan('app:dispatch-reminder-command');

    Queue::assertNotPushed(DispatchReminderJob::class);
});
