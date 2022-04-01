<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessCardCreateRequest;
use App\Http\Requests\BusinessCardGetRequest;
use App\Http\Resources\BusinessCardResource;
use App\Services\BusinessCardService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BusinessCardController extends Controller
{
    public function __construct(
        protected BusinessCardService $service
    ) {}

    public function create(BusinessCardCreateRequest $request)
    {
        return $this->success(
            $this->service->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    public function get(BusinessCardGetRequest $request)
    {
        return $this->success(
            new BusinessCardResource($this->service->get($request->id)),
            Response::HTTP_OK
        );
    }
}
