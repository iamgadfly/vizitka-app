<?php

namespace App\Repositories\WorkSchedule;

use App\Models\WorkScheduleDay;
use App\Models\WorkScheduleSettings;
use App\Repositories\Repository as BaseRepository;

class Repository extends BaseRepository
{
    protected static function getDayForNotSlidingSchedule(int $specialistId, string $weekday)
    {
        $settings = WorkScheduleSettings::where([
            'specialist_id' => $specialistId
        ])->first();
        return WorkScheduleDay::where([
            'day' => $weekday,
            'settings_id' => $settings->id
        ])->first();
    }

    protected static function getDayForSlidingSchedule(int $specialistId, int $dayIndex)
    {
        $settings = WorkScheduleSettings::where([
            'specialist_id' => $specialistId
        ])->first();
        return WorkScheduleDay::where([
            'day_index' => $dayIndex,
            'settings_id' => $settings->id
        ])->first();
    }
}
