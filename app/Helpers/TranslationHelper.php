<?php

namespace App\Helpers;

class TranslationHelper
{
    private static array $times = [
        0 => 'workSchedule.time.zeroMinutes',
        15 => 'workSchedule.time.fifteenMinutes',
        30 => 'workSchedule.time.halfHour',
        45 => 'workSchedule.time.threeQuarters',
        60 => 'workSchedule.time.oneHour',
        75 => 'workSchedule.time.oneHourFifteenMinutes',
        90 => 'workSchedule.time.oneHourHalfHour',
        105 => 'workSchedule.time.oneHourThreeQuarters',
        120 => 'workSchedule.time.twoHours',
        135 => 'workSchedule.time.twoHoursFifteenMinutes',
        150 => 'workSchedule.time.twoHoursHalfHour',
        165 => 'workSchedule.time.twoHoursThreeQuarters',
        180 => 'workSchedule.time.threeHours',
        195 => 'workSchedule.time.threeHoursFifteenMinutes',
        210 => 'workSchedule.time.threeHoursHalfHour',
        225 => 'workSchedule.time.threeHoursThreeQuarters',
        240 => 'workSchedule.time.fourHours',
        255 => 'workSchedule.time.fourHoursFifteenMinutes',
        270 => 'workSchedule.time.fourHoursHalfHour',
        285 => 'workSchedule.time.fourHoursThreeQuarters',
        300 => 'workSchedule.time.fiveHours',
        315 => 'workSchedule.time.fiveHoursFifteenMinutes',
        330 => 'workSchedule.time.fiveHoursHalfHour',
        345 => 'workSchedule.time.fiveHoursThreeQuarters',
        360 => 'workSchedule.time.sixHours',
        375 => 'workSchedule.time.sixHoursFifteenMinutes',
        390 => 'workSchedule.time.sixHoursHalfHour',
        405 => 'workSchedule.time.sixHoursThreeQuarters',
        420 => 'workSchedule.time.sevenHours',
        435 => 'workSchedule.time.sevenHoursFifteenMinutes',
        450 => 'workSchedule.time.sevenHoursHalfMinutes',
        465 => 'workSchedule.time.sevenHoursThreeQuarters',
        480 => 'workSchedule.time.eightHours',
        1440 => 'workSchedule.time.oneDay',
        2880 => 'workSchedule.time.twoDays',
        4320 => 'workSchedule.time.threeDays',
        10080 => 'workSchedule.time.oneWeek',
        20160 => 'workSchedule.time.twoWeeks',
        43800 => 'workSchedule.time.oneMonth',
        87600 => 'workSchedule.time.twoMonths',
        131400 => 'workSchedule.time.threeMonths',
        262800 => 'workSchedule.time.sixMonths',
        3153596 => 'workSchedule.time.oneYear'
    ];

    public static function getTranslationForTime(int $time)
    {
        return self::$times[$time];
    }
}
