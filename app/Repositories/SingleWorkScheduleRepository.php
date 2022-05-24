<?php

namespace App\Repositories;

use App\Models\SingleWorkSchedule;

class SingleWorkScheduleRepository extends Repository
{
    public function __construct(SingleWorkSchedule $model)
    {
        parent::__construct($model);
    }
}
