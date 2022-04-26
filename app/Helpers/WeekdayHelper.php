<?php

namespace App\Helpers;

class WeekdayHelper
{
    protected static $days = [
      'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'
    ];

    public static function getAll()
    {
        return self::$days;
    }
}
