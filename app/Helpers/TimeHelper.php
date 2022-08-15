<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function getTimeInterval(?string $start, ?string $end): array
    {
        if (is_null($start) || is_null($end)) {
            return [];
        }
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        $output = [$start->format('H:i')];
        while ($start->addMinutes(15) < $end) {
            $output[] = $start->format('H:i');
        }
        //TODO ПОЧЕМУ НЕЛЬЗЯ БЫЛО УДАЛИТЬ ЭТО А НЕ ВЫЗЫВАТЬ КОСТЫЛЬ ПОСТОЯННО В МИНУС 15 МИНУТ
//        if (!$isBreak) {
//            $output[] = $end->format('H:i');
//        }
        return $output;
    }

    /**
     * @param $date
     * @return array
     */
    public static function getMonthInterval($date): array
    {
        $first = Carbon::parse($date)->firstOfMonth();
        $last = Carbon::parse($date)->lastOfMonth();

        $output = [$first->format('Y-m-d')];
        while ($first->addDay() < $last) {
            $output[] = $first->format('Y-m-d');
        }
        $output[] = $last->format('Y-m-d');
        return $output;
    }

    /**
     * @param $date
     * @return array
     */
    public static function getMonthIntervalWithOutPastDates($date): array
    {
        $first = Carbon::now();
        $last = Carbon::parse($date)->lastOfMonth();

        $output = [$first->format('Y-m-d')];
        while ($first->addDay() < $last) {
            $output[] = $first->format('Y-m-d');
        }
        $output[] = $last->format('Y-m-d');
        return $output;
    }

    public static function getDateInterval(string $start, string $end): array
    {
        $first = Carbon::parse($start);
        $last = Carbon::parse($end);

        $output = [$first->format('Y-m-d')];
        while ($first->addDay() < $last) {
            $output[] = $first->format('Y-m-d');
        }
        $output[] = $last->format('Y-m-d');
        return $output;
    }

    public static function getTimeIntervalAsFreeAppointment(
        ?string $start, ?string $end, ?string $startDay, ?string $endDay
    ): array
    {
        $interval = self::getTimeInterval($start, $end);
        $output = [];
        for ($i = 0; $i < count($interval) - 1; $i++) {
            if ($interval[$i] < $startDay || $interval[$i + 1] > $endDay) {
                $output[] = [
                    'start' => $interval[$i],
                    'end' => $interval[$i + 1],
                    'status' => 'break'
                ];
            } else {
                $output[] = [
                    'start' => $interval[$i],
                    'end' => $interval[$i + 1],
                    'status' => 'free'
                ];
            }
        }
        return $output;
    }

    public static function getWeekdays(string $date): array
    {
        $dates = [];
        foreach (range(0, 6) as $day) {
            $dates[] = Carbon::parse($date)->startOfWeek()->addDay($day)->format('Y-m-d');
        }

        return $dates;
    }

    public static function formatDateForResponse(string $date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public static function getTimeIntervalAsInt(string $maxDate, string $minDate): int
    {
        $diff = Carbon::parse($maxDate)->diff(Carbon::parse($minDate));
        return ($diff->h * 60) + $diff->i;
    }

    private static function isInInterval($needle, $haystack): bool
    {
        return in_array($needle, $haystack);
    }
}
