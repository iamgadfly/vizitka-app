<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessCardCreateRequest;
use App\Http\Requests\BusinessCardGetRequest;
use App\Http\Resources\BusinessCardResource;
use App\Services\BusinessCardService;
use Illuminate\Http\Response;

class BusinessCardController extends Controller
{
    public function __construct(
        protected BusinessCardService $service
    ) {}

    public function get(BusinessCardGetRequest $request)
    {
        return $this->success(
            new BusinessCardResource($this->service->get($request->id)),
            Response::HTTP_OK
        );
    }

    public function update(BusinessCardCreateRequest $request)
    {
        return $this->success(
            $this->service->update($request->validated())
        );
    }
}
