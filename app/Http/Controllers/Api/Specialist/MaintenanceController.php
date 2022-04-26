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

    public function create(MaintenanceRequest $request)
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    public function get()
    {
        return $this->success(
            $this->service->getMySettings()
        );
    }
}
