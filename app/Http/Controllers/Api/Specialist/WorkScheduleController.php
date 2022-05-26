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

    /**
     * @return JsonResponse
     * @lrd:start
     * Get Work Schedule route
     * @lrd:end
     */
    public function get(): JsonResponse
    {
        return $this->success(
           $this->service->mySettings()
        );
    }
}
