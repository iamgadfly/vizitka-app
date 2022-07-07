<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientData\IdRequest;
use App\Http\Requests\ContactData\UpdateRequest;
use App\Services\Client\AppointmentService;
use App\Services\ClientService;
use App\Services\ContactDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        protected AppointmentService $service,
        protected ContactDataService $contactDataService
    ){}

    /**
     * @param IdRequest $request
     * @return JsonResponse
     * @throws ClientNotFoundException
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Get Client's history as Specialist
     * @lrd:end
     */
    public function getClientHistory(IdRequest $request)
    {
        return $this->success(
            $this->service->getMyHistory($request->type, $request->id)
        );
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function update(UpdateRequest $request)
    {
        return $this->success(
            $this->contactDataService->update($request->validated())
        );
    }
}
