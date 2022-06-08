<?php

namespace App\Repositories;

use App\Models\SingleWorkSchedule;
use App\Models\WorkScheduleBreak;
use App\Models\WorkScheduleDay;
use App\Models\WorkScheduleSettings;
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

    public static function getBreaksForADay($day)
    {
        return WorkScheduleBreak::whereHas('day', function ($q) use ($day) {
            $q->where('day', $day);
            return $q->whereHas('settings', function ($qb) {
                return $qb->where('specialist_id', auth()->user()->specialist->id);
            });
        })->get();
    }

    public static function getBreaksForADayIndex($index)
    {
        return WorkScheduleBreak::whereHas('day', function ($q) use ($index) {
            $q->where('day_index', $index);
            return $q->whereHas('settings', function ($qb) {
                return $qb->where('specialist_id', auth()->user()->specialist->id);
            });
        })->get();
    }

    public static function getBreaksForDay(string $date, bool $forCalendar = false)
    {
        $result = [];
        // Try to get single work schedule for a day
        $settings = WorkScheduleSettings::where([
            'specialist_id' => auth()->user()->specialist->id
        ])->first();
        if ($settings->type == 'sliding') {
            $index = WorkScheduleDayRepository::getDayIndexFromDate($date);
            $day_id = WorkScheduleDay::whereHas('settings', function ($q) {
                return $q->where('specialist_id', auth()->user()->specialist->id);
            })->where('day_index', $index->id)->first();
            $day_id = $day_id->id;
        } else {
            $weekday = strtolower(Carbon::parse($date)->shortEnglishDayOfWeek);
            $day_id = WorkScheduleDay::whereHas('settings', function ($q) {
                return $q->where('specialist_id', auth()->user()->specialist->id);
            })->where('day', $weekday)->first()->id;
        }
        $single = SingleWorkSchedule::where([
            'date' => $date,
            'day_id' => $day_id,
            'is_break' => true
        ])->get();

        if ($single->isNotEmpty()) {
            if ($forCalendar) {
                return $single;
            }
            foreach ($single as $break) {
                $result[] = [
                    $break->start,
                    $break->end
                ];
            }

            return $result;
        }
        // If not found single work schedule
        if ($settings->type == 'sliding') {
            $breaks = WorkScheduleBreak::whereHas('day', function ($q) use ($index) {
                $q->where('day_index', $index->id);
                return $q->whereHas('settings', function ($qb) {
                    return $qb->where('specialist_id', auth()->user()->specialist->id);
                });
            })->get();
        } else {
            $breaks = WorkScheduleBreak::whereHas('day', function ($q) use ($weekday) {
                $q->where('day', $weekday);
                return $q->whereHas('settings', function ($qb) {
                    return $qb->where('specialist_id', auth()->user()->specialist->id);
                });
            })->get();
        }
        if ($forCalendar) {
            return $breaks;
        }
        foreach ($breaks as $break) {
            $result[] = [
                $break->start,
                $break->end,
            ];
        }

        return $result;
    }

    public static function getBreaksForCalendar(string $date)
    {
        $breaks = self::getBreaksForADay($date);
        foreach ($breaks as $break) {
            $break = [
                'start' => $break[0],
                'end' => $break[1],
                'type' => 'break'
            ];
        }

        return $breaks;
    }
}
