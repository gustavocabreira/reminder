<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Reminder\StoreReminderRequest;
use App\Http\Requests\Reminder\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\Resources\Json\JsonResource;

final class ReminderController extends Controller
{
    /**
     * Cria um lembrete
     */
    public function index()
    {
        $reminders = Reminder::query()->orderByDesc('scheduled_at')->get();

        return ReminderResource::collection($reminders);
    }

    /**
     * Armazena um lembrete
     */
    public function store(StoreReminderRequest $request): JsonResource
    {
        $reminder = Reminder::query()->create($request->only('title', 'scheduled_at', 'entity', 'entity_id', 'notify_at'));

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

        return new ReminderResource($reminder);
    }

    /**
     * Deleta um lembrete
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return response()->noContent();
    }
}
