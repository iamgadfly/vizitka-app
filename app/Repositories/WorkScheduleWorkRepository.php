<?php

namespace App\Repositories;

use App\Models\WorkScheduleWork;

class WorkScheduleWorkRepository extends Repository
{
    public function __construct(WorkScheduleWork $model)
    {
        parent::__construct($model);
    }

    public function createWorkday(int $day_id, array $workday)
    {
        $workday['day_id'] = $day_id;
        return $this->create($workday);
    }

    public static function getWorks($settingsId)
    {
        return WorkScheduleWork::whereHas('day', function ($q) use ($settingsId) {
            return $q->whereHas('settings', function ($qb) use ($settingsId) {
                return $qb->where('id', $settingsId);
            });
        })->get();
    }
}
