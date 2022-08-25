<?php

namespace App\Helpers;

use Carbon\Carbon;

class RandomTimeGenerator
{
    public static function generateMinute($min = 15, $step = 15, $max= 240): int
    {
        $value = rand($min, $max);
        if ($value % $step !== 0) {
            return self::generateMinute($min, $step, $max);
        }
        return $value;
    }

    public static function getWorkTime($min = 0, $step = 15, $max = 1440): array
    {
        return self::generateTime($min, $step, $max);
    }

    public static function getBreakTime($min = 360, $step = 15, $max = 720): array
    {
        return self::generateTime($min, $step, $max);
    }

    /**
     * @param mixed $min
     * @param mixed $step
     * @param mixed $max
     * @return array
     */
    private static function generateTime(mixed $min, mixed $step, mixed $max): array
    {
        $start = self::generateMinute($min, $step, $max);
        $end = self::generateMinute($min, $step, $max);
        if ($start > $end) {
            $start = self::generateMinute();
        }
        $start = Carbon::now()->setTime(0, 0, 0)->addMinutes($start)->format("H:i");
        $end = Carbon::now()->setTime(0, 0, 0)->addMinutes($end)->format("H:i");
        return [$start, $end];
    }
}
