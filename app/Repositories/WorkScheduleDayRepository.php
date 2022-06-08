<?php

namespace App\Repositories;

use App\Helpers\WeekdayHelper;
use App\Models\WorkScheduleDay;
use App\Models\WorkScheduleSettings;
use Carbon\Carbon;

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
        foreach (range(1, $workdays + $weekends) as $day) {
            $output[] = $this->create([
                'settings_id' => $settings_id,
                'day_index' => $day,
            ]);
        }

        return $output;
    }

    public static function getDayIndexFromDate(string $date)
    {
        $settings = WorkScheduleSettings::where([
            'specialist_id' => auth()->user()->specialist->id
        ])->first();
        $dateFrom = Carbon::parse($settings->start_from);
        $date = Carbon::parse($date);
        $maxIndex = $settings->weekdays_count  + $settings->workdays_count;
        $index = 1;
        while ($dateFrom < $date) {
            $index++;
            if ($index > $maxIndex) {
                $index = 1;
            }
            $dateFrom->addDay();
        }
        return self::getDayFromInt($index);
    }

    public static function getDayFromString(string $day)
    {
        $day = WorkScheduleDay::whereHas('settings', function($q) {
            return $q->where('specialist_id', auth()->user()->specialist->id);
        })->where('day', $day)->get();

        return $day->first();
    }

    public static function getDayFromInt(int $id)
    {
        $day = WorkScheduleDay::whereHas('settings', function($q) {
            return $q->where('specialist_id', auth()->user()->specialist->id);
        })->where('day_index', $id)->get();

        return $day->first();
    }
}
