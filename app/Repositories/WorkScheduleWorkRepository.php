<?php

namespace App\Repositories;

use App\Models\SingleWorkSchedule;
use App\Models\WorkScheduleDay;
use App\Models\WorkScheduleWork;
use Carbon\Carbon;

class WorkScheduleWorkRepository extends Repository
{
    public function __construct(WorkScheduleWork $model)
    {
        parent::__construct($model);
    }

    public function createWorkday(int $day_id, array $workday)
    {
        $workday['day_id'] = $day_id;
        return $this->create($workday);
    }

    public static function getWorks($settingsId)
    {
        return WorkScheduleWork::whereHas('day', function ($q) use ($settingsId) {
            return $q->whereHas('settings', function ($qb) use ($settingsId) {
                return $qb->where('id', $settingsId);
            });
        })->get();
    }

    public static function getWorkDay(string $date)
    {
        // Try to get single work schedule for a day
        $weekday = strtolower(Carbon::parse($date)->shortEnglishDayOfWeek);
        $day_id = WorkScheduleDay::whereHas('settings', function ($q) {
            return $q->where('specialist_id', auth()->user()->specialist->id);
        })->where('day', $weekday)->first()->id;
        $single = SingleWorkSchedule::where([
            'date' => $date,
            'day_id' => $day_id,
            'is_break' => false
        ])->first();

        if (!is_null($single)) {
            return [
                Carbon::parse($single->start)->format('H:i'),
                Carbon::parse($single->end)->format('H:i')
            ];
        }
        // If not found single work schedule
        $day = WorkScheduleWork::whereHas('day', function ($q) use ($weekday) {
            $q->where('day', $weekday);
            return $q->whereHas('settings', function ($qb) {
                return $qb->where('specialist_id', auth()->user()->specialist->id);
            });
        })->get();
        if (is_null($day->first()?->start)) return null;
        return [
            Carbon::parse($day->first()->start)->format('H:i'),
            Carbon::parse($day->first()->end)->format('H:i')
        ];
    }
}
