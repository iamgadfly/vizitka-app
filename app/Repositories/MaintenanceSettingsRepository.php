<?php

namespace App\Repositories;

use App\Models\MaintenanceSettings;

class MaintenanceSettingsRepository extends Repository
{
    public function __construct(MaintenanceSettings $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        return $this->model::updateOrCreate(
            ['specialist_id' => $data['specialist_id']],
            $data
        );
    }

    public function mySettings()
    {
        $model = $this->model::whereHas('maintenances', function ($q) {
            $q->where('specialist_id', auth()->user()->specialist->id);
        });
        return $model->first();
    }
}
