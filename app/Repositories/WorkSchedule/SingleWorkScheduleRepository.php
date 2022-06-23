<?php

namespace App\Repositories\WorkSchedule;

use App\Models\SingleWorkSchedule;

class SingleWorkScheduleRepository extends Repository
{
    public function __construct(SingleWorkSchedule $model)
    {
        parent::__construct($model);
    }
}
