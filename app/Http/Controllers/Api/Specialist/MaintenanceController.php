<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\GetAllRequest;
use App\Http\Requests\MaintenanceRequest;
use App\Http\Requests\MaintenanceSettingsRequest;
use App\Http\Requests\NewMaintenanceRequest;
use App\Http\Resources\MaintenanceResource;
use App\Services\MaintenanceService;

class MaintenanceController extends Controller
{
    public function __construct(
        protected MaintenanceService $service
    ) {}

    public function create(NewMaintenanceRequest $request)
    {
        return $this->success(
            new MaintenanceResource($this->service->addNew($request->validated()))
        );
    }

    public function get()
    {
        return $this->success(
            $this->service->getMySettings()
        );
    }

    public function delete()
    {
        return $this->success(
            $this->service->delete()
        );
    }

    public function updateSettings(MaintenanceSettingsRequest $request)
    {
        return $this->success(
            $this->service->updateSettings($request->validated())
        );
    }

    public function update(MaintenanceRequest $request)
    {
        return $this->success(
            $this->service->update($request->validated())
        );
    }

    public function all(GetAllRequest $request)
    {
        return $this->success(
           MaintenanceResource::collection($this->service->all($request->specialist_id))
        );
    }
}
