<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Exceptions\TimeIsNotValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Appointment\CreateRequest;
use App\Http\Requests\SpecialistData\GetMyHistoryForThisSpecialistRequest;
use App\Http\Resources\Appointment\DuplicateResource;
use App\Http\Resources\AppointmentResource;
use App\Services\Client\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    /**
     * @param AppointmentService $service
     */
    public function __construct(
        protected AppointmentService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws TimeIsNotValidException
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Create Appointment as Client
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->create($request->validated())),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws ClientNotFoundException
     * @lrd:start
     * Check for duplicates route (For modals)
     * @lrd:end
     */
    public function checkForDuplicates(CreateRequest $request): JsonResponse
    {
        return $this->success(
            DuplicateResource::collection($this->service->checkForDuplicates($request->validated()))
        );
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws TimeIsNotValidException
     * @lrd:start
     * Update Appointment as Client
     * @lrd:end
     */
    public function update(CreateRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->update($request->validated()))
        );
    }

    /**
     * @return JsonResponse
     * @throws ClientNotFoundException
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Get History as Client
     * @lrd:end
     */
    public function getMyHistory(): JsonResponse
    {
        return $this->success(
            $this->service->getMyHistory()
        );
    }

    /**
     * @param GetMyHistoryForThisSpecialistRequest $request
     * @return JsonResponse
     * @lrd:start
     * History of entries for a particular specialist
     * @lrd:end
     */
    public function getHistoryForSpecialist(GetMyHistoryForThisSpecialistRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->getMyHistoryForSpecialist($request->client_id, $request->specialist_id)
        );
    }
}
