<?php

namespace Database\Factories;

use App\Helpers\CardBackgroundHelper;
use App\Models\BusinessCard;
use App\Models\Specialist;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessCard>
 */
class BusinessCardFactory extends Factory
{
    protected $model = BusinessCard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition()
    {
        return [
            'specialist_id' => Specialist::query()->doesntHave('card')->first()->id,
            'background_image' => CardBackgroundHelper::getCardFromActivityKind(
                CardBackgroundHelper::$files[random_int(0, count(CardBackgroundHelper::$files) -1)], false
            )->first()['url'],
            'title' => $this->faker->sentence(1),
            'about' => $this->faker->sentence(),
            'address' => $this->faker->streetAddress(),
            'placement' => $this->faker->numberBetween(1, 1000),
            'floor' => $this->faker->numberBetween(1, 100),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude()
        ];
    }
}
