<?php

namespace App\Repositories\WorkSchedule;

use App\Models\WorkScheduleDay;
use App\Repositories\Repository as BaseRepository;

class Repository extends BaseRepository
{
    protected static function getDayForNotSlidingSchedule(int $specialistId, string $weekday)
    {
        return WorkScheduleDay::whereHas('settings', function ($q) use ($specialistId) {
            return $q->where('specialist_id', $specialistId);
        })->where('day', $weekday)->first();
    }

    protected static function getDayForSlidingSchedule(int $specialistId, int $dayIndex)
    {
        return WorkScheduleDay::whereHas('settings', function ($q) use ($specialistId) {
            return $q->where('specialist_id', $specialistId);
        })->where('day_index', $dayIndex)->first();
    }
}
