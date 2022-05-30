<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\MaintenanceSettingsIsAlreadyExistingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\CreateRequest;
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

    /**
     * @throws MaintenanceSettingsIsAlreadyExistingException
     * @lrd:start
     * Create Maintenance on create specialist stage
     * @lrd:end
     */
    public function store(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    /**
     * @param NewMaintenanceRequest $request
     * @return JsonResponse
     * @lrd:start
     * Create Maintenance route
     * @lrd:end
     */
    public function create(NewMaintenanceRequest $request): JsonResponse
    {
        return $this->success(
            new MaintenanceResource($this->service->addNew($request->validated()))
        );
    }

    /**
     * @lrd:start
     * Get Maintenances route
     * @lrd:end
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        return $this->success(
            $this->service->getMySettings()
        );
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     * @lrd:start
     * Delete Maintenance route
     * @lrd:end
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    /**
     * @param MaintenanceSettingsRequest $request
     * @return JsonResponse
     * @lrd:start
     * Update Maintenance Settings route
     * @lrd:end
     */
    public function updateSettings(MaintenanceSettingsRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->updateSettings($request->validated())
        );
    }

    /**
     * @param MaintenanceRequest $request
     * @return JsonResponse
     * @lrd:start
     * Update Maintenance route
     * @lrd:end
     */
    public function update(MaintenanceRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->update($request->validated())
        );
    }

    /**
     * @param GetAllRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get all Maintenances route
     * @lrd:end
     */
    public function all(GetAllRequest $request): JsonResponse
    {
        return $this->success(
           MaintenanceResource::collection($this->service->all($request->specialist_id))
        );
    }
}
