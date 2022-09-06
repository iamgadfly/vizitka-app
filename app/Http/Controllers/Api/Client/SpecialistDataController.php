<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialistData\FreeHoursRequest;
use App\Http\Requests\SpecialistData\IdRequest;
use App\Services\SpecialistDataService;
use Illuminate\Http\JsonResponse;

class SpecialistDataController extends Controller
{
    public function __construct(
        protected SpecialistDataService $service
    ){}

    /**
     * @param FreeHoursRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Specialist's free hours for day
     * @lrd:end
     * @throws SpecialistNotFoundException
     */
    public function getFreeHours(FreeHoursRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->getFreeHours($request->id, $request->date, $request->sum, $request->hour)
        );
    }

    /**
     * @param IdRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Specialist's maintenances
     * @lrd:end
     */
    public function getMaintenances(IdRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->getSpecialistsMaintenances($request->id)
        );
    }


}
