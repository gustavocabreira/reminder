<?php

declare(strict_types=1);

namespace App\Http\Requests\Reminder;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date'],
            'entity' => ['nullable', 'string', Rule::in(['chat', 'contact'])],
            'entity_id' => ['nullable', 'integer', 'required_with:entity'],
            'notify_before_minutes' => ['required', 'int'],
            'notify_at' => ['sometimes', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('scheduled_at') && $this->has('notify_before_minutes')) {
            $scheduledAt = Carbon::parse($this->scheduled_at);
            $notifyAt = $scheduledAt->copy()->subMinutes($this->notify_before_minutes);

            $this->merge([
                'notify_at' => $notifyAt,
            ]);
        }
    }
}
