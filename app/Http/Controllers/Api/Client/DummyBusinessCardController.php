<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\DummyBusinessCard\DummyBusinessCardRequest;
use App\Http\Resources\DummyBusinessCardResource;
use App\Services\DummyBusinessCardService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DummyBusinessCardController extends Controller
{
    public function __construct(
        protected DummyBusinessCardService $service
    ){}

    public function create(DummyBusinessCardRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    public function update(DummyBusinessCardRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->update($request->validated()),
            Response::HTTP_OK
        );
    }

    public function delete(DummyBusinessCardRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id),
            Response::HTTP_OK
        );
    }

    public function get(DummyBusinessCardRequest $request): JsonResponse
    {
        return $this->success(
            DummyBusinessCardResource::make(
                $this->service->get($request->id),
                Response::HTTP_OK
            )
        );
    }
}
