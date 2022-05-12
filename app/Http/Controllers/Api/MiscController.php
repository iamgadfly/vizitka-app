<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetWeekDatesRequest;
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
     */
    public function getCountries(): JsonResponse
    {
        return $this->success(
            $this->service->getCountries()
        );
    }

    public function getBackgrounds(): JsonResponse
    {
        return $this->success(
            $this->service->getBackgrounds()
        );
    }

    public function getOnboardings(): JsonResponse
    {
        return $this->success(
            $this->service->getOnboardings()
        );
    }

    public function getActivityKinds(): JsonResponse
    {
        return $this->success(
            $this->service->getActivityKinds()
        );
    }

    public function getWeekDates(GetWeekDatesRequest $request)
    {
        return $this->success(
            $this->service->getWeekDates($request->date)
        );
    }
}
