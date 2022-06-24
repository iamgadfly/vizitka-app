<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function arrayWithoutIntersections(array $origin, array $second): array
    {
        if (is_array($second[0])) {
            $output = array_diff($origin, ...$second);
        } else {
            $output = array_diff($origin, $second);
        }
        return array_values($output);
    }
}
