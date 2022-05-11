<?php

namespace App\Repositories;

use App\Models\WorkScheduleBreak;
use Carbon\Carbon;

class WorkScheduleBreakRepository extends Repository
{
    public function __construct(WorkScheduleBreak $model)
    {
        parent::__construct($model);
    }

    public static function getBreaks($settingsId)
    {
        return WorkScheduleBreak::whereHas('day', function ($q) use ($settingsId) {
            return $q->whereHas('settings', function ($qb) use ($settingsId) {
                return $qb->where('id', $settingsId);
            });
        })->get();
    }

    public static function getBreaksForDay(string $date)
    {
        $result = [];
        $weekday = strtolower(Carbon::parse($date)->shortEnglishDayOfWeek);
        $breaks = WorkScheduleBreak::whereHas('day', function ($q) use ($weekday) {
            $q->where('day', $weekday);
            return $q->whereHas('settings', function ($qb) {
                return $qb->where('specialist_id', auth()->user()->specialist->id);
            });
        })->get();
        foreach ($breaks as $break) {
            $result[] = [
                $break->start,
                $break->end
            ];
        }

        return $result;
    }
}
