<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactBookForClient\CreateRequest;
use App\Http\Requests\ContactBookForClient\DeleteRequest;
use App\Http\Requests\ContactBookForClient\GetRequest;
use App\Http\Requests\ContactBookForClient\MassCreateRequest;
use App\Http\Resources\ContactBookForClientResource;
use App\Services\ContactBookForClientService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ContactBookController extends Controller
{
    public function __construct(
        protected ContactBookForClientService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws RecordIsAlreadyExistsException
     * @lrd:start
     * Add to Client's contact book
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->specialist_id),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param MassCreateRequest $request
     * @return JsonResponse
     * @throws RecordIsAlreadyExistsException
     * @lrd:start
     * Import to Client's contact book
     * @lrd:end
     */
    public function massCreate(MassCreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->massCreate($request->validated()),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     * @throws RecordNotFoundException
     * @lrd:start
     * Remove from Client's contact book
     * @lrd:end
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->specialist_id)
        );
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Client's contact book
     * @lrd:end
     */
    public function get(GetRequest $request): JsonResponse
    {
        return $this->success(
            ContactBookForClientResource::collection($this->service->get($request->client_id))
        );
    }
}
