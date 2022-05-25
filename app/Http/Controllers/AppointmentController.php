<?php

namespace App\Http\Controllers;

use App\Exceptions\TimeIsNotValidException;
use App\Http\Requests\Appointment\CreateOrUpdateRequest;
use App\Http\Requests\Appointment\GetAllByDayRequest;
use App\Http\Requests\Appointment\IdRequest;
use App\Http\Requests\Appointment\MassDeleteRequest;
use App\Http\Requests\GetSvgRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentResourceForCalendar;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $service
    ){}

    /**
     * @throws TimeIsNotValidException
     */
    public function create(CreateOrUpdateRequest $request)
    {
        return $this->success(
            new AppointmentResource($this->service->create($request->validated()))
        );
    }

    public function update(CreateOrUpdateRequest $request)
    {
        return $this->success(
            $this->service->update($request->validated())
        );
    }

    public function get(IdRequest $request)
    {
        return $this->success(
            new AppointmentResource($this->service->get($request->id))
        );
    }

    public function delete(IdRequest $request)
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    public function confirm(IdRequest $request)
    {
        return $this->success(
            $this->service->confirm($request->id)
        );
    }

    public function skipped(IdRequest $request)
    {
        return $this->success(
            $this->service->skipped($request->id)
        );
    }

    public function getAllByDay(GetAllByDayRequest $request)
    {
        $data = $this->service->getAllByDay($request->date);
        return response()->json(new AppointmentResourceForCalendar($data));
    }

    public function svgByMonth(GetSvgRequest $request)
    {
        return $this->success(
            $this->service->getSvgForPeriod($request->date)
        );
    }

    public function massDelete(MassDeleteRequest $request)
    {
        return $this->success(
            $this->service->massDelete($request->validated())
        );
    }
}
