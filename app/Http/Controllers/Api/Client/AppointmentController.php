<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\TimeIsNotValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Appointment\CreateRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\Client\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $service
    ){}

    /**
     * @throws TimeIsNotValidException
     */
    public function create(CreateRequest $request)
    {
        return $this->success(
            AppointmentResource::collection($this->service->create($request->validated()))
        );
    }

    /**
     * @throws TimeIsNotValidException
     */
    public function update(CreateRequest $request)
    {
        return $this->success(
            AppointmentResource::collection($this->service->update($request->validated()))
        );
    }

    /**
     * @throws ClientNotFoundException
     */
    public function getMyHistory()
    {
        return $this->success(
            $this->service->getMyHistory()
        );
    }
}
