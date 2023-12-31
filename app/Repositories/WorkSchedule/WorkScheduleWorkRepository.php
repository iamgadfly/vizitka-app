<?php

namespace App\Repositories\WorkSchedule;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Helpers\TimeHelper;
use App\Models\SingleWorkSchedule;
use App\Models\WorkScheduleDay;
use App\Models\WorkScheduleSettings;
use App\Models\WorkScheduleWork;
use Carbon\Carbon;

class WorkScheduleWorkRepository extends Repository
{
    public function __construct(WorkScheduleWork $model)
    {
        parent::__construct($model);
    }

    public static function getWorks($settingsId)
    {
        return WorkScheduleWork::whereHas('day', function ($q) use ($settingsId) {
            return $q->whereHas('settings', function ($qb) use ($settingsId) {
                return $qb->where('id', $settingsId);
            });
        })->get();
    }

    public static function getWeekends($settingsId)
    {
        return WorkScheduleWork::whereHas('day', function ($q) use ($settingsId) {
            return $q->whereHas('settings', function ($qb) use ($settingsId) {
                return $qb->where('id', $settingsId);
            });
        })->where([
            'start' => null,
            'end' => null
        ])->get();
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public static function getWorkDay(string $date, ?int $specialistId = null): ?array
    {
        if (is_null($specialistId)) {
            $specialistId = AuthHelper::getSpecialistIdFromAuth();
        }
        // Try to get single work schedule for a day
        $settings = WorkScheduleSettings::where([
            'specialist_id' => $specialistId
        ])->first();
        $weekday = strtolower(Carbon::parse($date)->shortEnglishDayOfWeek);
        if ($settings->type == 'sliding') {
            if (Carbon::parse($settings->start_from) > Carbon::parse($date)) {
                return null;
            }
            $day_id = WorkScheduleDayRepository::getDayIndexFromDate($date, $specialistId)->id;
        } else {
            $day_id = Repository::getDayForNotSlidingSchedule($specialistId, $weekday)->id;
        }

        $single = SingleWorkSchedule::where([
            'date' => $date,
            'day_id' => $day_id,
            'is_break' => false
        ])->first();
        if ($single) {
            return [
                TimeHelper::getFormattedTime($single->start),
                TimeHelper::getFormattedTime($single->end)
            ];
        }
        // If not found single work schedule
        $day = WorkScheduleWork::where(['day_id' => $day_id]);

        if (is_null($day->first()?->start)) return null;
        return [
            TimeHelper::getFormattedTime($day->first()->start),
            TimeHelper::getFormattedTime($day->first()->end)
        ];
    }
}
