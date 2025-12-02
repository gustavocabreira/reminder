<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'entity' => 'nullable|string|max:255',
            'entity_id' => 'nullable|integer',
            'notify_at'    => 'required|date',
        ];
    }
}
