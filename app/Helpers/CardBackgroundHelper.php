<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
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
        'resnizi', 'visagiste', 'browist'
    ];

    private static $colors = [
        'barber' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#D0D0D0',
            'icons' => '#000000'
        ],
        'hairdresser_1' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#F4E8EA',
            'icons' => '#68213D'
        ],
        'hairdresser_2' => [
            'title' => '#301F05',
            'name' => '#301F05',
            'description' => '#4E330B',
            'icons' => '#301F05'
        ],
        'manicure_1' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#F5E9EC',
            'icons' => '#89233D'
        ],
        'manicure_2' => [
            'title' => '#6B2F49',
            'name' => '#6B2F49',
            'description' => '#7D3354',
            'icons' => '#6B2E49'
        ],
        'neutral_beauty_1' => [
            'title' => '#452225',
            'name' => '#452225',
            'description' => '#452225',
            'icons' => '#5E1A1F'
        ],
        'neutral_beauty_2' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#D9EBF7',
            'icons' => '#314D5E'
        ],
        'neutral_man_1' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#F5F5F5',
            'icons' => '#120B10'
        ],
        'neutral_man_2' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#FFFFFF',
            'icons' => '#1B93AD'
        ],
        'neutral_woman_1' => [
            'title' => '#361C47',
            'name' => '#361C47',
            'description' => '#57326F',
            'icons' => '#2D163D'
        ],
        'neutral_woman_2' => [
            'title' => '#361C47',
            'name' => '#5E3F40',
            'description' => '#5E3F40',
            'icons' => '#914348'
        ],
        'psychology_1' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#DFF1EF',
            'icons' => '#196358'
        ],
        'psychology_2' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#E8F1F3',
            'icons' => '#095A7C'
        ],
        'resnizi' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#FFFFFF',
            'icons' => '#5B2F8C'
        ],
        'visagiste' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#F4EAED',
            'icons' => '#BD637B'
        ],
        'browist' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#FFFFFF',
            'icons' => '#8F6D61'
        ],
    ];

    private static function getAssetFromFilename(string $filename): string
    {
        return asset(Storage::url($filename));
    }

    public static function filenameFromActivityKind(string $activityKind): ?string
    {
        if (in_array($activityKind, self::$files)) {
            return self::$imagePath . $activityKind . '.png';
        }

        return null;
    }

    public static function getAll(): Collection
    {
        return collect(Storage::disk('public')->files('/images/card_backgrounds'))->map(function ($file) {
            $name = str(basename($file))->explode('.')[0];

            if ($name == 'default') return;

            return [
                'name' => $name,
                'colors' => self::$colors[$name],
                'url' => self::getAssetFromFilename($file)
            ];
        });
    }
}
