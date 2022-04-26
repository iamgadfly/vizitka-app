<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkScheduleRequest;
use App\Http\Resources\WorkScheduleSettingsResource;
use App\Services\WorkScheduleService;

class WorkScheduleController extends Controller
{
    public function __construct(
        protected WorkScheduleService $service
    ){}

    /**
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     */
    public function create(WorkScheduleRequest $request)
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    public function get()
    {
        return $this->success(
           $this->service->mySettings()
        );
    }
}
