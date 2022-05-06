<?php

namespace App\Repositories;

use App\Models\WorkScheduleBreak;

class WorkScheduleBreakRepository extends Repository
{
    public function __construct(WorkScheduleBreak $model)
    {
        parent::__construct($model);
    }

    public static function getBreaks($settingsId)
    {
        return WorkScheduleBreak::whereHas('day', function ($q) use ($settingsId) {
            return $q->whereHas('settings', function ($qb) use ($settingsId) {
                return $qb->where('id', $settingsId);
            });
        })->get();
    }
}
