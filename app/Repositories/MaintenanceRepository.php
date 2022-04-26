<?php

namespace App\Repositories;

use App\Models\Maintenance;

class MaintenanceRepository extends Repository
{
    public function __construct(Maintenance $model)
    {
        parent::__construct($model);
    }
}
