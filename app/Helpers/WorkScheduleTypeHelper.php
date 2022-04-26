<?php

namespace App\Helpers;

class WorkScheduleTypeHelper
{
    protected static array $translations = [
        'sliding' => 'misc.work_schedule.types.sliding',
        'flexible' => 'misc.work_schedule.types.flexible',
        'standard' => 'misc.work_schedule.types.standard'
    ];

    public static function get(string $type)
    {
        return __(self::$translations[$type]);
    }
}
