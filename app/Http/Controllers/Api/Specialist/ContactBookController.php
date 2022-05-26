<?php

namespace App\Http\Controllers\Api\Specialist;

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

    public function create(CreateRequest $request)
    {

    }

    public function delete(DeleteRequest $request)
    {

    }

    public function massCreate(MassCreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->massCreate($request->validated())
        );
    }

    public function get(GetRequest $request): JsonResponse
    {
        return $this->success(
            ContactBookResource::collection($this->service->get($request->specialist_id))
        );
    }
}
