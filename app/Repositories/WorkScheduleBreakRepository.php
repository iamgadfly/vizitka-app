<?php

namespace App\Repositories;

use App\Models\WorkScheduleBreak;

class WorkScheduleBreakRepository extends Repository
{
    public function __construct(WorkScheduleBreak $model)
    {
        parent::__construct($model);
    }
}
