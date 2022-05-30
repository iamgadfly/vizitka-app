<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blacklist\CreateRequest;
use App\Http\Requests\Blacklist\DeleteRequest;
use App\Http\Requests\Blacklist\GetRequest;
use App\Http\Resources\BlacklistResource;
use App\Services\BlacklistService;
use Illuminate\Http\JsonResponse;

class BlacklistController extends Controller
{
    public function __construct(
        protected BlacklistService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @lrd:start
     * Add to blacklist route
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     * @lrd:start
     * Remove from blacklist route
     * @lrd:end
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get clients in blacklist
     * @lrd:end
     */
    public function get(GetRequest $request)
    {
        return $this->success(
            BlacklistResource::collection($this->service->get($request->specialist_id))
        );
    }
}
