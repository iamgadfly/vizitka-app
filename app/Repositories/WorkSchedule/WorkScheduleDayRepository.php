<?php

namespace App\Repositories\WorkSchedule;

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

    public function fillDaysNotForSlidingType(int $settings_id): array
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

    public function fillDaysForSlidingType(int $settings_id, int $workdays, int $weekends): array
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

    public static function getDayIndexFromDate(string $date, ?int $specialistId = null)
    {
        if (is_null($specialistId)) {
            $specialistId = Repository::getSpecialistIdFromAuth();
        }
        $settings = WorkScheduleSettings::where([
            'specialist_id' => $specialistId
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
        return self::getDayFromInt($index, $specialistId);
    }

    public static function getDayFromString(string $day, ?int $specialistId = null)
    {
        if (is_null($specialistId)) {
            $specialistId = Repository::getSpecialistIdFromAuth();
        }
        $day = Repository::getDayForNotSlidingSchedule($specialistId, $day);

        return $day->first();
    }

    public static function getDayFromInt(int $index, ?int $specialistId = null)
    {
        if (is_null($specialistId)) {
            $specialistId = Repository::getSpecialistIdFromAuth();
        }
        $day = Repository::getDayForSlidingSchedule($specialistId, $index);

        return $day->first();
    }
}
