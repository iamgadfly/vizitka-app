<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactBook\CreateRequest;
use App\Http\Requests\ContactBook\DeleteRequest;
use App\Http\Requests\ContactBook\GetRequest;
use App\Http\Requests\ContactBook\MassCreateRequest;
use App\Http\Resources\ContactBookResource;
use App\Services\ContactBookService;
use Illuminate\Http\JsonResponse;

class ContactBookController extends Controller
{
    public function __construct(
        protected ContactBookService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws RecordIsAlreadyExistsException
     * @lrd:start
     * Add client to contact book
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            new ContactBookResource($this->service->create($request->client_id))
        );
    }

    public function delete(DeleteRequest $request)
    {

    }

    /**
     * @param MassCreateRequest $request
     * @return JsonResponse
     * @lrd:start
     * Mass create contacts route (import from phone contacts)
     * @lrd:end
     * @throws RecordIsAlreadyExistsException
     */
    public function massCreate(MassCreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->massCreate($request->validated())
        );
    }

    /**
     * @param GetRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Contact Book route
     * @lrd:end
     */
    public function get(GetRequest $request): JsonResponse
    {
        return $this->success(
            ContactBookResource::collection($this->service->get($request->specialist_id))
        );
    }
}
