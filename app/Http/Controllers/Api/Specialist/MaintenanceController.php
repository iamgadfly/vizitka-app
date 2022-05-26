<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\DeleteRequest;
use App\Http\Requests\Maintenance\GetAllRequest;
use App\Http\Requests\Maintenance\MaintenanceRequest;
use App\Http\Requests\Maintenance\MaintenanceSettingsRequest;
use App\Http\Requests\Maintenance\NewMaintenanceRequest;
use App\Http\Resources\MaintenanceResource;
use App\Services\MaintenanceService;
use Illuminate\Http\JsonResponse;

class MaintenanceController extends Controller
{
    public function __construct(
        protected MaintenanceService $service
    ) {}

    public function create(NewMaintenanceRequest $request): JsonResponse
    {
        return $this->success(
            new MaintenanceResource($this->service->addNew($request->validated()))
        );
    }

    public function get(): JsonResponse
    {
        return $this->success(
            $this->service->getMySettings()
        );
    }

    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    public function updateSettings(MaintenanceSettingsRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->updateSettings($request->validated())
        );
    }

    public function update(MaintenanceRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->update($request->validated())
        );
    }

    public function all(GetAllRequest $request): JsonResponse
    {
        return $this->success(
           MaintenanceResource::collection($this->service->all($request->specialist_id))
        );
    }
}
