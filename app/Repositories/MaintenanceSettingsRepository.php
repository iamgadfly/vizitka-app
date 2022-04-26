<?php

namespace App\Repositories;

use App\Models\MaintenanceSettings;

class MaintenanceSettingsRepository extends Repository
{
    public function __construct(MaintenanceSettings $model)
    {
        parent::__construct($model);
    }

    public function mySettings()
    {
        $model = $this->model::whereHas('maintenances', function ($q) {
            $q->where('specialist_id', auth()->user()->specialist->id);
        });
        return $model->first();
    }
}
