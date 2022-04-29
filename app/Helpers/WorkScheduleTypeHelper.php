<?php

namespace App\Helpers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;

class WorkScheduleTypeHelper
{
    protected static array $translations = [
        'sliding' => 'misc.work_schedule.types.sliding',
        'flexible' => 'misc.work_schedule.types.flexible',
        'standard' => 'misc.work_schedule.types.standard'
    ];

    public function getAllKeys(): array
    {
        return array_keys(self::$translations);
    }

    public static function get(string $type)
    {
        return __(self::$translations[$type]);
    }
}
