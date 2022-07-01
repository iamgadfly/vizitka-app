<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSchedule\CreateRequest;
use App\Http\Requests\WorkSchedule\UpdateRequest;
use App\Services\WorkScheduleService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WorkScheduleController extends Controller
{
    public function __construct(
        protected WorkScheduleService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws SpecialistNotFoundException
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     * @lrd:start
     * Create Work Schedule on create specialist stage
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param UpdateRequest $request
     * @return JsonResponse
     * @throws RecordNotFoundException
     * @throws SpecialistNotFoundException
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     * @lrd:start
     * Update Work Schedule
     * @lrd:end
     */
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
