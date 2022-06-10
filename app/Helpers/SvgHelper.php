<?php

namespace App\Helpers;

class SvgHelper
{
    public static array $colors = [
        'unconfirmed' => '#38B8E0',
        'confirmed' => '#52AA63',
        'skipped' => '#D64641',
        'break' => '#EBEDEF',
        'free' => '#FFFFFF'
    ];

    public static function getColorFromType(string $color)
    {
        return self::$colors[$color];
    }
}
