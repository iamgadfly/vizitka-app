<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\TimeIsNotValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\CreateOrUpdateRequest;
use App\Http\Requests\Appointment\GetAllByDayRequest;
use App\Http\Requests\Appointment\GetSvgRequest;
use App\Http\Requests\Appointment\IdRequest;
use App\Http\Requests\Appointment\MassDeleteRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentResourceForCalendar;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $service
    ){}

    /**
     * @throws TimeIsNotValidException
     */
    public function create(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->create($request->validated()))
        );
    }

    public function update(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->update($request->validated()))
        );
    }

    public function get(IdRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->get($request->order_number))
        );
    }

    public function delete(IdRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    public function confirm(IdRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->confirm($request->id)
        );
    }

    public function skipped(IdRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->skipped($request->id)
        );
    }

    public function getAllByDay(GetAllByDayRequest $request): JsonResponse
    {
        $data = $this->service->getAllByDay($request->date);
        return response()->json(new AppointmentResourceForCalendar($data));
    }

    public function svgByMonth(GetSvgRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->getSvgForPeriod($request->date)
        );
    }

    public function massDelete(MassDeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->massDelete($request->validated())
        );
    }
}
