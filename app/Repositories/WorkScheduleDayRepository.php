<?php

namespace App\Repositories;

use App\Helpers\WeekdayHelper;
use App\Models\WorkScheduleDay;

class WorkScheduleDayRepository extends Repository
{
    public function __construct(WorkScheduleDay $model)
    {
        parent::__construct($model);
    }

    public function fillDaysNotForSlidingType(int $settings_id)
    {
        $output = [];
        foreach (WeekdayHelper::getAll() as $day) {
            $output[] = $this->create([
                'settings_id' => $settings_id,
                'day' => $day
            ]);
        }
        return $output;
    }

    public function fillDaysForSlidingType(int $settings_id, int $workdays, int $weekends)
    {
        $output = [];
        foreach (range(1, $workdays) as $day) {
            $output[] = $this->create([
                'settings_id' => $settings_id,
                'day_index' => $day,
            ]);
        }

        foreach (range($workdays + 1, $workdays + $weekends) as $day) {
            $output[] = $this->create([
                'settings_id' => $settings_id,
                'day_index' => $day,
            ]);
        }

        return $output;
    }
}
