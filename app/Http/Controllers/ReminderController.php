<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Reminder\StoreReminderRequest;
use App\Http\Requests\Reminder\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

final class ReminderController extends Controller
{
    /**
     * Cria um lembrete
     */
    public function index()
    {
        $reminders = Cache::remember('company:1:users:1:reminders', 60, function () {
            return Reminder::query()->orderByDesc('scheduled_at')->get();
        });

        return ReminderResource::collection($reminders);
    }

    /**
     * Armazena um lembrete
     */
    public function store(StoreReminderRequest $request): JsonResource
    {
        $reminder = Reminder::query()->create($request->only('title', 'scheduled_at', 'entity', 'entity_id', 'notify_at'));
        Cache::forget('company:1:users:1:reminders');

        return new ReminderResource($reminder);
    }

    /**
     * Mostra um lembrete
     */
    public function show(Reminder $reminder): JsonResource
    {
        return new ReminderResource($reminder);
    }

    /**
     * Atualiza um lembrete
     */
    public function update(Reminder $reminder, UpdateReminderRequest $request): JsonResource
    {
        $reminder->update($request->only('title', 'scheduled_at', 'entity', 'entity_id', 'notify_at'));
        Cache::forget('company:1:users:1:reminders');

        return new ReminderResource($reminder);
    }

    /**
     * Deleta um lembrete
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        Cache::forget('company:1:users:1:reminders');

        return response()->noContent();
    }
}
