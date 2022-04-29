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

    public function fillDays(int $settings_id)
    {
        foreach (WeekdayHelper::getAll() as $day) {
            $this->create([
                'setting_id' => $settings_id,
                'day' => $day
            ]);
        }
    }
}
