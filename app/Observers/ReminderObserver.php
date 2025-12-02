<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Reminder;

final class ReminderObserver
{
    public function creating(Reminder $reminder): void
    {
        $reminder->company_id = 1;
        $reminder->user_id = 1;
    }

    /**
     * Handle the Reminder "created" event.
     */
    public function created(Reminder $reminder): void
    {
        //
    }

    /**
     * Handle the Reminder "updated" event.
     */
    public function updated(Reminder $reminder): void
    {
        //
    }

    /**
     * Handle the Reminder "deleted" event.
     */
    public function deleted(Reminder $reminder): void
    {
        //
    }

    /**
     * Handle the Reminder "restored" event.
     */
    public function restored(Reminder $reminder): void
    {
        //
    }

    /**
     * Handle the Reminder "force deleted" event.
     */
    public function forceDeleted(Reminder $reminder): void
    {
        //
    }
}
