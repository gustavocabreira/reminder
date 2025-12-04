<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\NotifyReminderEvent;
use App\Models\Reminder;
use App\Models\User;
use App\Notifications\ReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

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
            'is_notified' => true,
            'notified_at' => now(),
        ]);

        $this->reminder->user->notify(new ReminderNotification($this->reminder));

        $user = User::find($this->reminder->user_id);

        if ($this->reminder->entity === 'contact') {
            $response = Http::withToken($user->token)->get(config('services.huggy.api_url').'/contacts/'.$this->reminder->entity_id);
            $data = collect($response->json())->only('id', 'name')->toArray();
            $data['id'] = (int) $data['id'];
            $this->reminder->setAttribute('entity_data', $data);
        } elseif ($this->reminder->entity === 'chat') {
            $response = Http::withToken($user->token)->get(config('services.huggy.api_url').'/chats/'.$this->reminder->entity_id);
            $data = collect($response->json())->only('id', 'name')->toArray();
            $data['id'] = (int) $data['id'];
            $this->reminder->setAttribute('entity_data', $data);
        }

        NotifyReminderEvent::dispatch($this->reminder);
    }
}
