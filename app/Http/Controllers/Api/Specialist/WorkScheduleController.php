<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Services\WorkScheduleService;

class WorkScheduleController extends Controller
{
    public function __construct(
        protected WorkScheduleService $service
    ){}

    public function get()
    {
        return $this->success(
           $this->service->mySettings()
        );
    }
}
