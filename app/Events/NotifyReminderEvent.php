<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Reminder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class NotifyReminderEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Reminder $reminder,
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("users.{$this->reminder->user_id}.reminders"),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'reminder.notify';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->reminder->id,
            'user_id' => $this->reminder->user_id,
            'title' => $this->reminder->title,
            'scheduled_at' => $this->reminder->scheduled_at,
            'entity' => $this->reminder->entity,
            'entity_id' => $this->reminder->entity_id,
            'notify_at' => $this->reminder->notify_at,
            'entity_data' => isset($this->reminder->entity_data) ? $this->reminder->entity_data : null,
        ];
    }
}
