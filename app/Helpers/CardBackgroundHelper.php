<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CardBackgroundHelper
{
    /**
     * @var string
     */
    private static $imagePath = 'images/card_backgrounds/';

    /**
     * @var array<string>
     */
    public static $files = [
        'barber', 'hairdresser_1', 'hairdresser_2',
        'manicure_1', 'manicure_2', 'default',
        'neutral_beauty_1', 'neutral_beauty_2',
        'neutral_man_1', 'neutral_man_2',
        'neutral_woman_1', 'neutral_woman_2',
        'psychology_1', 'psychology_2',
        'resnizi', 'visagiste', 'browist'
    ];

    private static $specialistColor = [
        'barber' => [
            'gradientColor' => '#414141',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#000000'
        ],
        'hairdresser_1' => [
            'gradientColor' => '#C57280',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#68213D'
        ],
        'hairdresser_2' => [
            'gradientColor' => '#E7BA67',
            'textColor' => '#000000',
            'buttonsColor' => '#301F05'
        ],
        'manicure_1' => [
            'gradientColor' => '#E15D7F',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#89233D'
        ],
        'manicure_2' => [
            'gradientColor' => '#FFD9EA',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#6B2E49'
        ],
        'neutral_beauty_1' => [
            'gradientColor' => '#EFBAB8',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#5E1A1F'
        ],
        'neutral_beauty_2' => [
            'gradientColor' => '#416780',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#314D5E'
        ],
        'neutral_man_1' => [
            'gradientColor' => '#312A28',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#120B10'
        ],
        'neutral_man_2' => [
            'gradientColor' => '#3C4556',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#1F2634'
        ],
        'neutral_woman_1' => [
            'gradientColor' => '#E4D1F0',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#2D163D'
        ],
        'neutral_woman_2' => [
            'gradientColor' => '#F7D6D9',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#904348'
        ],
        'psychology_1' => [
            'gradientColor' => '#5EB7AA',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#196358'
        ],
        'psychology_2' => [
            'gradientColor' => '#6CC4D7',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#095A7C'
        ],
        'resnizi' => [
            'gradientColor' => '#7B4ACB',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#5B2F8C'
        ],
        'visagiste' => [
            'gradientColor' => '#BD637B',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#753A49'
        ],
        'browist' => [
            'gradientColor' => '#9D8071',
            'textColor' => '#FFFFFF',
            'buttonsColor' => '#715A4F'
        ],
    ];

    /**
     * @var array<array<string>>
     */
    private static $colors = [
        'default' => [
            'title' => '#FFFFFF',
            'name' => '#FFFFFF',
            'description' => '#FFFFFF',
            'icons' => '#1C7F9E'
        ],
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
            return self::$imagePath . $activityKind . '.jpg';
        }

        return null;
    }

    public static function getActivityKindFromFilename(string $filename): string
    {
        return str($filename)
            ->replace('images/card_backgrounds/', '')
            ->replace('.jpg', '')
            ->value();
    }

    public static function getSpecialistColorFromActivityKind(string $activityKind): ?array
    {
        return self::$specialistColor[$activityKind];
    }

    public static function getActivityKindFromFilename(string $filename)
    {
        return str($filename)
               ->replace('images/card_backgrounds/', '')
               ->replace('.jpg', '')
               ->value();
    }

    public static function getCardFromActivityKind(string $activityKind): Collection
    {
        return collect(Storage::disk('public')->files('/images/card_backgrounds'))->map(function ($file) {
            $name = str(basename($file))->explode('.')[0];

            return [
                'nameBusiness' => $name,
                'colors' => self::$colors[$name],
                'url' => self::getAssetFromFilename($file)
            ];
        })->reject(function ($element) use ($activityKind) {
            return $element['nameBusiness'] != $activityKind;
        })->values();
    }

    public static function getAll(): Collection
    {
        return collect(Storage::disk('public')->files('/images/card_backgrounds'))->map(function ($file) {
            $name = str(basename($file))->explode('.')[0];

            return [
                'nameBusiness' => $name,
                'colors' => self::$colors[$name],
                'url' => self::getAssetFromFilename($file)
            ];
        })->reject(function ($element) {
            return $element['nameBusiness'] == 'default';
        })->values();
    }
}
