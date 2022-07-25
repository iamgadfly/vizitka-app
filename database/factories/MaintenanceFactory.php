<?php

namespace Database\Factories;

use App\Helpers\RandomTimeGenerator;
use App\Models\MaintenanceSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maintenance>
 */
class MaintenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'settings_id' => MaintenanceSettings::all()->random()->id,
            'title' => $this->faker->jobTitle(),
            'price' => $this->faker->numberBetween(500, 5000),
            'duration' => RandomTimeGenerator::generateMinute()
        ];
    }
}
