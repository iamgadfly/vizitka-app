<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessCard\BusinessCardCreateRequest;
use App\Http\Requests\BusinessCard\BusinessCardGetRequest;
use App\Http\Resources\BusinessCardResource;
use App\Services\BusinessCardService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BusinessCardController extends Controller
{
    public function __construct(
        protected BusinessCardService $service
    ) {}

    public function get(BusinessCardGetRequest $request): JsonResponse
    {
        return $this->success(
            new BusinessCardResource($this->service->get($request->id)),
            Response::HTTP_OK
        );
    }

    public function update(BusinessCardCreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->update($request->validated()),
            Response::HTTP_OK
        );
    }
}
