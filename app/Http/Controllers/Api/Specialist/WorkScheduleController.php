<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSchedule\CreateRequest;
use App\Http\Requests\WorkSchedule\UpdateRequest;
use App\Services\WorkScheduleService;
use Illuminate\Http\JsonResponse;

class WorkScheduleController extends Controller
{
    public function __construct(
        protected WorkScheduleService $service
    ){}

    /**
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     * @lrd:start
     * Create Work Schedule on create specialist stage
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    public function update(UpdateRequest $request)
    {
        return $this->success(
            $this->service->update($request->validated())
        );
    }

    /**
     * @return JsonResponse
     * @lrd:start
     * Get Work Schedule route
     * @lrd:end
     */
    public function get(): JsonResponse
    {
        return $this->success(
           $this->service->mySettings()
        );
    }
}
