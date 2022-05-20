<?php

namespace App\Helpers;

class SvgHelper
{
    public static array $colors = [
        'unconfirmed' => '#0000FF',
        'confirmed' => '#00FF00',
        'skipped' => '#FF0000',
        'break' => '#888888',
        'free' => '#FFFFFF'
    ];

    public static function getColorFromType(string $color)
    {
        return self::$colors[$color];
    }
}
