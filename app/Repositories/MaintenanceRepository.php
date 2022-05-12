<?php

namespace App\Repositories;

use App\Models\Maintenance;

class MaintenanceRepository extends Repository
{
    public function __construct(Maintenance $model)
    {
        parent::__construct($model);
    }

    public function allForCurrentUser(int $specialistId)
    {
        return $this->model::where('specialist_id', $specialistId)->get();
    }
}
