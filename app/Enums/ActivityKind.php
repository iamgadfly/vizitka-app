<?php

namespace App\Enums;

enum ActivityKind: int
{
    case Hairdresser = 1;
    case Manicurist = 2;
    case DepilationMastert = 3;
    case Visagiste = 4;
    case Browist = 5;
    case Lashmaker = 6;
    case Masseur = 7;
    case Psychologist = 8;
    case Fitness trainer = 9;
    case Photographer = 10;
    case Tutor = 11;
    case Other = 12;

    public static function fromInt(int $id): string
    {
        return match($id) {
            1 => 'Парикмахер',
            2 => 'Мастер маникюра',
            3 => 'Мастер депиляции',
            4 => 'Визажист',
            5 => 'Бровист',
            6 => 'Лэшмейкер',
            7 => 'Массажист',
            8 => 'Психолог ',
            9 => 'Фитнес-тренер',
            10 => 'Фотограф',
            11 => 'Репетитор',
            12 => 'Другое',
        };
    }
}
