<?php

declare(strict_types=1);

namespace Database\Factories;

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
        return [
            'company_id' => 1,
            'user_id' => 1,
            'title' => fake()->sentence(),
            'scheduled_at' => fake()->dateTime(),
            'notified_at' => null,
            'is_notified' => false,
            'current_step' => 0,
        ];
    }
}
