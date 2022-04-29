<?php

namespace App\Repositories;

use App\Models\WorkScheduleWork;

class WorkScheduleWorkRepository extends Repository
{
    public function __construct(WorkScheduleWork $model)
    {
        parent::__construct($model);
    }
}
