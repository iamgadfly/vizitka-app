<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Services\WorkScheduleService;
use Illuminate\Http\JsonResponse;

class WorkScheduleController extends Controller
{
    public function __construct(
        protected WorkScheduleService $service
    ){}

    public function get(): JsonResponse
    {
        return $this->success(
           $this->service->mySettings()
        );
    }
}
