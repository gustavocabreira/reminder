<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Reminder\StoreReminderRequest;
use App\Http\Requests\Reminder\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class ReminderController extends Controller
{
    /**
     * Listar lembretes
     */
    public function index()
    {
        if (! request()->has('from') && ! request()->has('to') && ! request()->has('date')) {
            $reminders = Cache::remember('company:1:users:1:reminders', 60, function () {
                return Reminder::query()->orderByDesc('scheduled_at')->get();
            });
        } else {
            $reminders = Reminder::query()
                ->when(request()->has('from'), function ($query) {
                    return $query->whereDate('scheduled_at', '>=', request('from'));
                })
                ->when(request()->has('to'), function ($query) {
                    return $query->whereDate('scheduled_at', '<=', request('to'));
                })
                ->when(request()->has('date'), function ($query) {
                    return $query->whereDate('scheduled_at', '=', request('date'));
                })
                ->orderByDesc('scheduled_at')
                ->cursorPaginate(10);
        }

        $array = $reminders
            ->filter(fn ($reminder) => ! is_null($reminder->entity))
            ->groupBy('entity')
            ->map(fn ($group) => $group->pluck('entity_id')->toArray())
            ->toArray();

        $responses = Http::pool(fn (Pool $pool) => [
            $pool->withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/contacts', [
                'query' => [
                    'ids' => array_values($array['contact']),
                ],
            ]),
            $pool->withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/chats', [
                'query' => [
                    'ids' => array_values($array['chat']),
                ],
            ]),
        ]);

        $contacts = $responses[0]->json();
        $chats = $responses[1]->json();

        $contactsById = collect($contacts)->map(fn ($item) => ['id' => (int) $item['id'], 'name' => $item['name']])->keyBy('id');
        $chatsById = collect($chats)->map(fn ($item) => ['id' => (int) $item['id']])->keyBy('id');

        $reminders = $reminders->map(function ($reminder) use ($contactsById, $chatsById) {
            $data = null;

            if ($reminder->entity === 'contact') {
                $data = $contactsById->get($reminder->entity_id);
            }

            if ($reminder->entity === 'chat') {
                $data = $chatsById->get($reminder->entity_id);
            }

            $reminder->setAttribute('entity_data', $data);

            return $reminder;
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
