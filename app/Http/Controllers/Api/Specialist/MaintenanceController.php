<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceRequest;
use App\Services\MaintenanceService;

class MaintenanceController extends Controller
{
    public function __construct(
        protected MaintenanceService $service
    ) {}

    public function get()
    {
        return $this->success(
            $this->service->getMySettings()
        );
    }

    public function delete()
    {
//        return $this->success(
//            $this->service->delete()
//        );
    }

    public function update()
    {

    }
}
