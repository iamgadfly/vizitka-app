<?php

namespace App\Repositories\WorkSchedule;

use App\Models\SingleWorkSchedule;
use App\Models\WorkScheduleBreak;
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

    public static function getBreaksForADay(string $day, ?int $specialistId = null)
    {
        if (is_null($specialistId)) {
            $specialistId = Repository::getSpecialistIdFromAuth();
        }
        $day_id = self::getDayForNotSlidingSchedule($specialistId, $day)->id;
        return WorkScheduleBreak::where([
            'day_id' => $day_id
        ])->get();
    }

    public static function getBreaksForADayIndex(int $index, ?int $specialistId)
    {
        if (is_null($specialistId)) {
            $specialistId = Repository::getSpecialistIdFromAuth();
        }
        $day_id = self::getDayForSlidingSchedule($specialistId, $index);
        return WorkScheduleBreak::where([
            'day_id' => $day_id
        ])->get();
    }

    public function getBreaksForDay(string $date, bool $forCalendar = false, int $specialist = null)
    {
        if (is_null($specialist)) {
            $specialist = $this->getSpecialistIdFromAuth();
        }
        $result = [];
        // Try to get single work schedule for a day
        $settings = WorkScheduleSettings::where([
            'specialist_id' => $specialist
        ])->first();
        if ($settings->type == 'sliding') {
            $day_id = WorkScheduleDayRepository::getDayIndexFromDate($date)->id;
        } else {
            $weekday = strtolower(Carbon::parse($date)->shortEnglishDayOfWeek);
            $day_id = self::getDayForNotSlidingSchedule($specialist, $weekday)->id;
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
        $breaks = WorkScheduleBreak::where(['day_id' => $day_id])->get();

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

    public function getBreaksForCalendar(string $date, ?int $specialistId = null)
    {
        if (is_null($specialistId)) {
            $specialistId = $this->getSpecialistIdFromAuth();
        }
        $breaks = self::getBreaksForADay($date, $specialistId);
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
