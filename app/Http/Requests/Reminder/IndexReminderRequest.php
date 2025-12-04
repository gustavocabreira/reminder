<?php

declare(strict_types=1);

namespace App\Http\Requests\Reminder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class IndexReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date'],
            'date' => ['sometimes', 'date'],
            'groupBy' => ['sometimes', 'string', Rule::in(['day'])],
        ];
    }
}
