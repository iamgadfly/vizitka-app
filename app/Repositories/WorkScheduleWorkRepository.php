<?php

namespace App\Repositories;

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
        $weekday = strtolower(Carbon::parse($date)->shortEnglishDayOfWeek);
        $day = WorkScheduleWork::whereHas('day', function ($q) use ($weekday) {
            $q->where('day', $weekday);
            return $q->whereHas('settings', function ($qb) {
                return $qb->where('specialist_id', auth()->user()->specialist->id);
            });
        })->get();
        if (is_null($day->first()->start)) return [];
        return [
            Carbon::parse($day->first()->start)->format('H:i'),
            Carbon::parse($day->first()->end)->format('H:i')
        ];
    }
}
