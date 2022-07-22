<?php

namespace Database\Factories;

use App\Models\Specialist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialist>
 */
class SpecialistFactory extends Factory
{
    protected $model = Specialist::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'activity_kind_id' => $this->faker->numberBetween(1, 8),
            'avatar_id' => null,
            'user_id' => User::query()->doesntHave('specialist')->doesntHave('client')->first()->id
        ];
    }
}
