<?php

namespace App\Repositories;

use App\Models\WorkScheduleSettings;

class WorkScheduleSettingsRepository extends Repository
{
    public function __construct(WorkScheduleSettings $model)
    {
        parent::__construct($model);
    }

    public function mySettings()
    {
        $model = $this->model::where('specialist_id', auth()->user()->specialist->id);
        return $model->first();
    }
}
