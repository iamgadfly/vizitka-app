<?php

namespace App\Enums;

enum ActivityKind: int
{
    case Hairdresser = 1;
    case MakeupArtist = 2;
    case Psychologist = 3;
    case MassageTherapist = 4;
    case SpeechTherapist = 5;
    case Nutritionist = 6;
    case FitnessTrainer = 7;
    case Other = 8;

    public static function fromInt(int $id): string
    {
        return match($id) {
            1 => 'Парикмахер',
            2 => 'Визажист',
            3 => 'Психолог',
            4 => 'Массажист',
            5 => 'Логопед',
            6 => 'Диетолог',
            7 => 'Фитнес-тренер',
            8 => 'Другое',
        };
    }
}
