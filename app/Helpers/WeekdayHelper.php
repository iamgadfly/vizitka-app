<?php

namespace App\Helpers;

class WeekdayHelper
{
    protected static array $days = [
      'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'
    ];

    public static function getAll(): array
    {
        return self::$days;
    }
}
