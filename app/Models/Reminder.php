<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\ReminderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(ReminderObserver::class)]
final class Reminder extends Model
{
    /** @use HasFactory<\Database\Factories\ReminderFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'scheduled_at',
        'notify_at',
        'entity',
        'entity_id',
        'notified_at',
        'is_notified',
        'current_step',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'user_id' => 'integer',
        'scheduled_at' => 'datetime',
        'notify_at' => 'datetime',
        'notified_at' => 'datetime',
        'is_notified' => 'boolean',
        'current_step' => 'integer',
        'entity_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
