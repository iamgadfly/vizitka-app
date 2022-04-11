<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MiscService;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function __construct(
        protected MiscService $service
    ){}

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
}
