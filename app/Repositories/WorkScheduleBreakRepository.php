<?php

namespace App\Repositories;

use App\Models\SingleWorkSchedule;
use App\Models\WorkScheduleBreak;
use App\Models\WorkScheduleDay;
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
        // Try to get single work schedule for a day
        $weekday = strtolower(Carbon::parse($date)->shortEnglishDayOfWeek);
//        $day_id = WorkScheduleDay::whereHas([
//            'specialist_id' => ,
//            'day' => $weekday
//        ])->first()->id;
        $day_id = WorkScheduleDay::whereHas('settings', function ($q) {
            return $q->where('specialist_id', auth()->user()->specialist->id);
        })->where('day', $weekday)->first()->id;
        $single = SingleWorkSchedule::where([
            'date' => $date,
            'day_id' => $day_id,
            'is_break' => true
        ])->get();

        if (!empty($single)) {
            foreach ($single as $break) {
                $result[] = [
                    $break->start,
                    $break->end
                ];
            }

            return $result;
        }
        // If not found single work schedule
        $result = [];
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
