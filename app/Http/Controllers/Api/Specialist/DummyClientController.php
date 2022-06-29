<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DummyClient\CreateOrUpdateRequest;
use App\Http\Requests\DummyClient\DeleteRequest;
use App\Http\Requests\DummyClient\GetAllRequest;
use App\Http\Requests\DummyClient\GetRequest;
use App\Http\Resources\DummyClientResource;
use App\Services\DummyClientService;
use Illuminate\Http\JsonResponse;

class DummyClientController extends Controller
{
    public function __construct(
        protected DummyClientService $service
    ){}

    /**
     * @param CreateOrUpdateRequest $request
     * @return JsonResponse
     * @throws RecordIsAlreadyExistsException
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Create Dummy Client route
     * @lrd:end
     */
    public function create(CreateOrUpdateRequest $request): JsonResponse
    {
        return $this->success(
            new DummyClientResource($this->service->create($request->validated()))
        );
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Dummy Client route
     * @lrd:end
     */
    public function get(GetRequest $request): JsonResponse
    {
        return $this->success(
            new DummyClientResource($this->service->get($request->id))
        );
    }

    /**
     * @param CreateOrUpdateRequest $request
     * @return JsonResponse
     * @lrd:start
     * Update Dummy Client route
     * @lrd:end
     */
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
