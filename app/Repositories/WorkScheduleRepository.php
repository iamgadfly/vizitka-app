<?php

namespace App\Repositories;

use App\Models\WorkSchedule;

class WorkScheduleRepository extends Repository
{
    public function __construct(WorkSchedule $model)
    {
        parent::__construct($model);
    }
}
