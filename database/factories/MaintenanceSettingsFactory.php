<?php

namespace Database\Factories;

use App\Models\Specialist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceSettings>
 */
class MaintenanceSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'finance_analytics' => $this->faker->boolean(),
            'many_maintenances' => $this->faker->boolean(),
            'specialist_id' => Specialist::query()->doesntHave('maintenanceSettings')->get()->random()->id
        ];
    }
}
