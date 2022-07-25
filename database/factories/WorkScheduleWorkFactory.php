<?php

namespace Database\Factories;

use App\Helpers\RandomTimeGenerator;
use App\Models\WorkScheduleDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkScheduleWork>
 */
class WorkScheduleWorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $workTime = RandomTimeGenerator::getBreakTime();
        $start = fake()->randomElement([$workTime[0], null]);
        $end = is_null($start) ? null : $workTime[1];
        return [
            'start' => $start,
            'end' => $end,
            'day_id' => WorkScheduleDay::query()->doesntHave('work')->get()->random()->id
        ];
    }
}
