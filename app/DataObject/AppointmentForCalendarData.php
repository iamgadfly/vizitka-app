<?php

namespace App\DataObject;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class AppointmentForCalendarData extends Data
{
    public function __construct(
        public Collection $appointments,
        public array $disabled,
        public array $workSchedule,
        public bool $smartSchedule
    ){}
}
