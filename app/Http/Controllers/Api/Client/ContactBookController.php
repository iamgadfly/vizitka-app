<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactBookForClient\CreateRequest;
use App\Http\Requests\ContactBookForClient\DeleteRequest;
use App\Http\Requests\ContactBookForClient\GetRequest;
use App\Http\Requests\ContactBookForClient\MassCreateRequest;
use App\Services\ContactBookService;
use Illuminate\Http\JsonResponse;

class ContactBookController extends Controller
{
    public function __construct(
        protected ContactBookService $service
    ){}

    /**
     * @throws RecordIsAlreadyExistsException
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->specialist_id)
        );
    }

    /**
     * @throws RecordIsAlreadyExistsException
     */
    public function massCreate(MassCreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->massCreate($request->validated())
        );
    }

    /**
     * @throws RecordNotFoundException
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->specialist_id)
        );
    }

    public function get(GetRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->get($request->client_id)
        );
    }
}
