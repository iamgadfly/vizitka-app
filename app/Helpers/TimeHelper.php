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

    public static function getWeekdays(string $date): array
    {
        $dates = [];
        foreach (range(0, 6) as $day) {
            $dates[] = Carbon::parse($date)->startOfWeek()->addDay($day)->format('Y-m-d');
        }

        return $dates;
    }

    public static function formatDateForResponse(string $date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}
