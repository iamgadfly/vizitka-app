<?php

namespace Database\Factories;

use App\Helpers\RandomTimeGenerator;
use App\Models\Specialist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkScheduleSettings>
 */
class WorkScheduleSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = fake()->randomElement(['standard', 'flexible', 'sliding']);
        return [
            'smart_schedule' => fake()->boolean(),
            'confirmation' => fake()->boolean(),
            'cancel_appointment' => RandomTimeGenerator::generateMinute(60, 15, 43200),
            'limit_before' => RandomTimeGenerator::generateMinute(60, 15, 43200),
            'limit_after' => RandomTimeGenerator::generateMinute(60, 15, 43200),
            'type' => $type,
            'break_type' => fake()->randomElement(['united', 'individual']),
            'start_from' => fake()->date('Y-m-d'),
            'workdays_count' => $type == 'sliding' ? fake()->numberBetween(1, 7) : null,
            'weekdays_count' => $type == 'sliding' ? fake()->numberBetween(1, 7) : null,
            'specialist_id' => Specialist::query()->doesntHave('scheduleSettings')->get()->random()->id
        ];
    }
}
