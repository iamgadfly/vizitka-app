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
        $output[] = $end->format('H:i');
        return $output;
    }

    public static function getMonthInterval($date)
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

    public static function getTimeIntervalAsFreeAppointment(?string $start, ?string $end): array
    {
        $interval = self::getTimeInterval($start, $end);
        $output = [];
        for ($i = 0; $i < count($interval) - 1; $i++) {
            $output[] = [
                'start' => $interval[$i],
                'end' => $interval[$i + 1],
                'status' => 'free'
            ];
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
