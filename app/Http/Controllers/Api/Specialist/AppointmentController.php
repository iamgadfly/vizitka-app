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
     * @lrd:start
     * Create Appointment route
     * @lrd:end
     */
    public function create(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            AppointmentResource::collection($this->service->create($request->validated()))
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
     */
    public function getAllByDay(GetAllByDayRequest $request): JsonResponse
    {

//        $data = $this->service->getAllByDay($request->date);
        $string = '{
            "smart_schedule": false,
            "data": [
                {
                    "order_number": "22667",
                    "date": "2022-06-16",
                    "status": "unconfirmed",
                    "interval": [
                        "11:00",
                        "11:15",
                        "11:30"
                    ],
                    "services": [
                      {
                        "id": 90,
                        "status": "unconfirmed",
                        "title": "test",
                        "price": {
                            "label": "0",
                            "value": 0
                        },
                        "duration": {
                            "label": "15",
                            "value": 15
                        }
                      },
                      {
                        "id": 91,
                        "title": "test",
                        "price": {
                            "label": "0",
                            "value": 0
                        },
                        "duration": {
                            "label": "15",
                            "value": 15
                        }
                      }
                   ],
                    "client": {
                        "id": 48,
                        "name": "Kek",
                        "surname": "Kekov",
                        "phone_number": "+7 999 999 99 99",
                        "photo": null,
                        "discount": 10
                    }
                }
            ],
            "workSchedule": [
                "10:00",
                "10:15",
                "10:30",
                "10:45",
                "11:00",
                "11:15",
                "11:30",
                "11:45",
                "12:00",
                "12:15",
                "12:30",
                "12:45",
                "13:00",
                "13:15",
                "13:30",
                "13:45",
                "14:00",
                "14:15",
                "14:30",
                "14:45",
                "15:00",
                "15:15",
                "15:30",
                "15:45",
                "16:00",
                "16:15",
                "16:30",
                "16:45",
                "17:00",
                "17:15",
                "17:30",
                "17:45",
                "18:00",
                "18:15",
                "18:30",
                "18:45",
                "19:00"
            ],
            "time_interval": [
                "00:00",
                "00:15",
                "00:30",
                "00:45",
                "01:00",
                "01:15",
                "01:30",
                "01:45",
                "02:00",
                "02:15",
                "02:30",
                "02:45",
                "03:00",
                "03:15",
                "03:30",
                "03:45",
                "04:00",
                "04:15",
                "04:30",
                "04:45",
                "05:00",
                "05:15",
                "05:30",
                "05:45",
                "06:00",
                "06:15",
                "06:30",
                "06:45",
                "07:00",
                "07:15",
                "07:30",
                "07:45",
                "08:00",
                "08:15",
                "08:30",
                "08:45",
                "09:00",
                "09:15",
                "09:30",
                "09:45",
                "10:00",
                "10:15",
                "10:30",
                "10:45",
                "11:00",
                "11:15",
                "11:30",
                "11:45",
                "12:00",
                "12:15",
                "12:30",
                "12:45",
                "13:00",
                "13:15",
                "13:30",
                "13:45",
                "14:00",
                "14:15",
                "14:30",
                "14:45",
                "15:00",
                "15:15",
                "15:30",
                "15:45",
                "16:00",
                "16:15",
                "16:30",
                "16:45",
                "17:00",
                "17:15",
                "17:30",
                "17:45",
                "18:00",
                "18:15",
                "18:30",
                "18:45",
                "19:00",
                "19:15",
                "19:30",
                "19:45",
                "20:00",
                "20:15",
                "20:30",
                "20:45",
                "21:00",
                "21:15",
                "21:30",
                "21:45",
                "22:00",
                "22:15",
                "22:30",
                "22:45",
                "23:00",
                "23:15",
                "23:30",
                "23:45"
            ]
        }';
        return response()->json(json_decode($string));
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
}
