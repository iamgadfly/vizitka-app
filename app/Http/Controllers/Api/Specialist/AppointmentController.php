<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\BaseException;
use App\Exceptions\SpecialistNotFoundException;
use App\Exceptions\TimeIsNotValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\CreateOrUpdateRequest;
use App\Http\Requests\Appointment\GetAllByDayRequest;
use App\Http\Requests\Appointment\GetAppointmentInIntervalRequest;
use App\Http\Requests\Appointment\GetSvgRequest;
use App\Http\Requests\Appointment\IdRequest;
use App\Http\Requests\Appointment\MassDeleteRequest;
use App\Http\Requests\Appointment\RemoveAppointmentsBetweenTwoDatesRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentResourceForCalendar;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $service
    ){}

    /**
     * @throws TimeIsNotValidException
     * @lrd:start
     * Create Appointment route
     * @lrd:end
     */
    public function create(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->create($request->validated())),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param CreateOrUpdateRequest $request
     * @return JsonResponse
     * @throws TimeIsNotValidException
     * @lrd:start
     * Update Appointment route
     * @lrd:end
     */
    public function update(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->update($request->validated()))
        );
    }

    /**
     * @param IdRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Appointment route
     * @lrd:end
     */
    public function get(IdRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->get($request->order_number))
        );
    }

    /**
     * @param IdRequest $request
     * @return JsonResponse
     * @lrd:start
     * Delete Appointment route
     * @lrd:end
     */
    public function delete(IdRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->order_number)
        );
    }

    /**
     * @param IdRequest $request
     * @return JsonResponse
     * @lrd:start
     * Confirm Appointment route
     * @lrd:end
     */
    public function confirm(IdRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->confirm($request->order_number)
        );
    }

    /**
     * @param IdRequest $request
     * @return JsonResponse
     * @lrd:start
     * Skipped Appointment route
     * @lrd:end
     */
    public function skipped(IdRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->skipped($request->order_number)
        );
    }

    /**
     * @param GetAllByDayRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get All appointments by day route
     * @lrd:end
     * @throws SpecialistNotFoundException
     */
    public function getAllByDay(GetAllByDayRequest $request): JsonResponse
    {
        $data = $this->service->getAllByDay($request->date);
        return response()->json(new AppointmentResourceForCalendar($data));
    }

    /**
     * @throws BaseException
     */
    public function getAppointmentsInInterval(GetAppointmentInIntervalRequest $request)
    {
        return $this->success(
            $this->service->getAppointmentsInInterval($request->validated())
        );
    }

    /**
     * @param GetSvgRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get SVG data for a month
     * @lrd:end
     */
    public function svgByMonth(GetSvgRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->getSvgForPeriod($request->dates)
        );
    }

    /**
     * @param MassDeleteRequest $request
     * @return JsonResponse
     * @lrd:start
     * Mass delete appointments route
     * @lrd:end
     */
    public function massDelete(MassDeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->massDelete($request->validated())
        );
    }

    public function deleteAppointmentsBetweenTwoDates(RemoveAppointmentsBetweenTwoDatesRequest $request)
    {
        return $this->success(
            $this->service->deleteAppointmentsBetweenTwoDates($request->validated())
        );
    }
}
