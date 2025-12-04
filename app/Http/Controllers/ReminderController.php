<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Reminder\IndexReminderRequest;
use App\Http\Requests\Reminder\StoreReminderRequest;
use App\Http\Requests\Reminder\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class ReminderController extends Controller
{
    /**
     * Listar lembretes
     */
    public function index(IndexReminderRequest $request)
    {
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
            ->orderByRaw('notified_at IS NULL DESC')
            ->orderBy('scheduled_at', 'asc')
            ->cursorPaginate(10);

        $array = $reminders
            ->filter(fn ($reminder) => ! is_null($reminder->entity))
            ->groupBy('entity')
            ->map(fn ($group) => $group->pluck('entity_id')->toArray())
            ->toArray();

        $contacts = [];
        $chats = [];

        if (isset($array['contact'])) {
            $contacts = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/contacts', [
                'query' => [
                    'ids' => array_values($array['contact']),
                ],
            ])->json();
        }

        if (isset($array['chat'])) {
            $chats = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/chats', [
                'query' => [
                    'ids' => array_values($array['chat']),
                ],
            ])->json();
        }

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

        if (request()->has('groupBy') && request()->groupBy === 'day') {
            $collection = ReminderResource::collection($reminders);

            $grouped = collect($collection->collection)
                ->groupBy(fn ($r) => $r->scheduled_at->format('Y-m-d'))
                ->sortKeys();

            $collection->collection = $grouped;

            return $collection;
        }

        return ReminderResource::collection($reminders);

    }

    /**
     * Armazena um lembrete
     */
    public function store(StoreReminderRequest $request): JsonResource
    {
        $reminder = Reminder::query()->create($request->only('title', 'scheduled_at', 'entity', 'entity_id', 'notify_at'));

        if ($reminder->entity === 'contact') {
            $response = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/contacts/'.$reminder->entity_id);
            $data = collect($response->json())->only('id', 'name')->toArray();
            $data['id'] = (int) $data['id'];
            $reminder->setAttribute('entity_data', $data);
        } elseif ($reminder->entity === 'chat') {
            $response = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/chats/'.$reminder->entity_id);
            $data = collect($response->json())->only(['id'])->toArray();
            $reminder->setAttribute('entity_data', $data);
        }

        Cache::forget('company:1:users:1:reminders');

        return new ReminderResource($reminder);
    }

    /**
     * Mostra um lembrete
     */
    public function show(Reminder $reminder): JsonResource
    {
        if ($reminder->entity === 'contact') {
            $response = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/contacts/'.$reminder->entity_id);
            $data = collect($response->json())->only('id', 'name')->toArray();
            $data['id'] = (int) $data['id'];
            $reminder->setAttribute('entity_data', $data);
        } elseif ($reminder->entity === 'chat') {
            $response = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/chats/'.$reminder->entity_id);
            $data = collect($response->json())->only(['id'])->toArray();
            $reminder->setAttribute('entity_data', $data);
        }

        return new ReminderResource($reminder);
    }

    /**
     * Atualiza um lembrete
     */
    public function update(Reminder $reminder, UpdateReminderRequest $request): JsonResource
    {
        $reminder->update($request->only('title', 'scheduled_at', 'entity', 'entity_id', 'notify_at'));
        Cache::forget('company:1:users:1:reminders');

        if ($reminder->entity === 'contact') {
            $response = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/contacts/'.$reminder->entity_id);
            $data = collect($response->json())->only('id', 'name')->toArray();
            $data['id'] = (int) $data['id'];
            $reminder->setAttribute('entity_data', $data);
        } elseif ($reminder->entity === 'chat') {
            $response = Http::withToken(auth()->user()->token)->get(config('services.huggy.api_url').'/chats/'.$reminder->entity_id);
            $data = collect($response->json())->only(['id'])->toArray();
            $reminder->setAttribute('entity_data', $data);
        }

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
