<?php

namespace App\Http\Controllers;

use App\Http\Requests\DummyClient\CreateOrUpdateRequest;
use App\Http\Requests\DummyClient\DeleteRequest;
use App\Http\Requests\DummyClient\GetAllRequest;
use App\Http\Requests\DummyClient\GetRequest;
use App\Http\Resources\DummyClientResource;
use App\Services\DummyClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DummyClientController extends Controller
{
    public function __construct(
        protected DummyClientService $service
    ){}


    public function create(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            new DummyClientResource($this->service->create($request->validated()))
        );
    }

    public function get(GetRequest $request): JsonResponse
    {
        return $this->success(
            new DummyClientResource($this->service->get($request->id))
        );
    }

    public function update(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->update($request->validated())
        );
    }

    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    public function all(GetAllRequest $request): JsonResponse
    {
        return $this->success(
            DummyClientResource::collection($this->service->all($request->specialist_id))
        );
    }
}
