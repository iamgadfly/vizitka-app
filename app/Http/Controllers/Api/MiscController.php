<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\GetWeekDatesRequest;
use App\Services\MiscService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

class MiscController extends Controller
{
    public function __construct(
        protected MiscService $service
    ){}

    /**
     * @throws GuzzleException
     * @lrd:start
     * Get Countries route
     * @lrd:end
     */
    public function getCountries(): JsonResponse
    {
        return $this->success(
            $this->service->getCountries()
        );
    }

    /**
     * @return JsonResponse
     * @lrd:start
     * Get Backgrounds route
     * @lrd:end
     */
    public function getBackgrounds(): JsonResponse
    {
        return $this->success(
            $this->service->getBackgrounds()
        );
    }

    /**
     * @return JsonResponse
     * @lrd:start
     * Get Onboarding route
     * @lrd:end
     */
    public function getOnboardings(): JsonResponse
    {
        return $this->success(
            $this->service->getOnboardings()
        );
    }

    /**
     * @return JsonResponse
     * @lrd:start
     * Get Activity Kinds route
     * @lrd:end
     */
    public function getActivityKinds(): JsonResponse
    {
        return $this->success(
            $this->service->getActivityKinds()
        );
    }

    /**
     * @param GetWeekDatesRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Week Dates route
     * @lrd:end
     */
    public function getWeekDates(GetWeekDatesRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->getWeekDates($request->date)
        );
    }
}
