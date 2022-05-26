<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactBook\CreateRequest;
use App\Http\Requests\ContactBook\DeleteRequest;
use App\Http\Requests\ContactBook\GetRequest;
use App\Http\Requests\ContactBook\MassCreateRequest;
use App\Http\Resources\ContactBookResource;
use App\Services\ContactBookService;

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

    public function massCreate(MassCreateRequest $request)
    {
        return $this->success(
            $this->service->massCreate($request->validated())
        );
    }

    public function get(GetRequest $request)
    {
        return $this->success(
            ContactBookResource::collection($this->service->get($request->specialist_id))
        );
    }
}
