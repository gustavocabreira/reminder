<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class DispatchReminderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Reminder $reminder)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->reminder->update([
            'current_step' => 2,
        ]);

        // TODO: Send notification to user
        $this->reminder->user->notify(new ReminderNotification($this->reminder));
    }
}
