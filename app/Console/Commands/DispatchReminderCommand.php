<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\DispatchReminderJob;
use App\Models\Reminder;
use Illuminate\Console\Command;

final class DispatchReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-reminder-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches the reminder to the user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Reminder::query()
            ->where('is_notified', false)
            ->where('current_step', 0)
            ->where('notify_at', '<=', now()->addMinutes(10))
            ->chunk(100, function ($reminders) {
                $reminders->each(function ($reminder) {
                    $reminder->update([
                        'current_step' => 1,
                    ]);

                    DispatchReminderJob::dispatch($reminder);
                });
            });
    }
}
