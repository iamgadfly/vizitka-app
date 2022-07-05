<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactBook\CreateRequest;
use App\Http\Requests\ContactBook\DeleteRequest;
use App\Http\Requests\ContactBook\GetRequest;
use App\Http\Requests\ContactBook\MassCreateRequest;
use App\Http\Requests\ContactBook\MassDeleteRequest;
use App\Http\Resources\ContactBookResource;
use App\Services\ContactBookService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ContactBookController extends Controller
{
    public function __construct(
        protected ContactBookService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws RecordIsAlreadyExistsException
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Add client to contact book
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            new ContactBookResource($this->service->create($request->client_id)),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     * @throws RecordNotFoundException
     * @lrd:start
     * Remove from Specialist's contact book
     * @lrd:end
     */
    public function delete(DeleteRequest $request)
    {
        return $this->success(
            $this->service->delete($request->validated())
        );
    }

    /**
     * @param MassCreateRequest $request
     * @return JsonResponse
     * @lrd:start
     * Mass create contacts route (import from phone contacts)
     * @lrd:end
     * @throws RecordIsAlreadyExistsException
     * @throws SpecialistNotFoundException
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

    /**
     * @throws SpecialistNotFoundException
     */
    public function massDelete(MassDeleteRequest $request)
    {
        return $this->success(
            $this->service->massDelete($request->validated())
        );
    }
}
