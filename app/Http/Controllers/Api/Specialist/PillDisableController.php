<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PillDisable\CreateRequest;
use App\Http\Requests\PillDisable\DeleteRequest;
use App\Services\PillDisableService;

class PillDisableController extends Controller
{
    public function __construct(
        protected PillDisableService $service
    ){}

    public function create(CreateRequest $request)
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    public function delete(DeleteRequest $request)
    {
        return $this->success(
            $this->service->delete($request->time, $request->date)
        );
    }
}
