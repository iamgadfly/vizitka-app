<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class CardBackgroundHelper
{
    private static $imagePath = 'images/card_backgrounds/';

    public static $files = [
        'barber', 'hairdresser_1', 'hairdresser_2',
        'manicure_1', 'manicure_2', 'default',
        'neutral_beauty_1', 'neutral_beauty_2',
        'neutral_man_1', 'neutral_man_2',
        'neutral_woman_1', 'neutral_woman_2',
        'psychology_1', 'psychology_2',
        'resnizi', 'visagiste'
    ];

    public static function getAssetFromFilename(string $filename)
    {
        return asset(Storage::url($filename));
    }

    public static function filenameFromActivityKind(string $activityKind): string|null
    {
        if (in_array($activityKind, self::$files)) {
            return self::$imagePath . $activityKind . '.svg';
        }

        return null;
    }

    public static function getAll()
    {
        return collect(Storage::disk('public')->files('/images/card_backgrounds'))->map(function ($file) {
            return self::getAssetFromFilename($file);
        });
    }
}
