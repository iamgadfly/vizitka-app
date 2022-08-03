<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blacklist\CreateRequest;
use App\Http\Requests\Blacklist\DeleteRequest;
use App\Http\Requests\Blacklist\GetRequest;
use App\Http\Resources\BlacklistResource;
use App\Services\BlacklistService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BlacklistController extends Controller
{
    public function __construct(
        protected BlacklistService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws SpecialistNotFoundException
     * @throws RecordIsAlreadyExistsException
     * @lrd:start
     * Add to blacklist route
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     * @throws RecordNotFoundException
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Remove from blacklist route
     * @lrd:end
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->blacklisted_id, $request->type)
        );
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get clients in blacklist
     * @lrd:end
     */
    public function get(GetRequest $request): JsonResponse
    {
        return $this->success(
            BlacklistResource::collection($this->service->get($request->specialist_id))
        );
    }
}
