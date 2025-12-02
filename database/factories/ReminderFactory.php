<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
final class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scheduledAt = Carbon::now()->addDays(rand(1, 7))->setHour(14);

        return [
            'company_id' => 1,
            'user_id' => 4,
            'title' => fake()->sentence(3),
            'scheduled_at' => $scheduledAt,
            'notify_at' => $scheduledAt->copy()->subMinutes(20),
            'notified_at' => null,
            'is_notified' => false,
            'current_step' => 0,
        ];
    }
}
