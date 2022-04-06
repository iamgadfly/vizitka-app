<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\DummyBusinessCardRequest;
use App\Http\Resources\DummyBusinessCardResource;
use App\Services\DummyBusinessCardService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DummyBusinessCardController extends Controller
{
    public function __construct(
        protected DummyBusinessCardService $service
    ){}

    public function create(DummyBusinessCardRequest $request)
    {
        return $this->success(
            $this->service->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    public function update(DummyBusinessCardRequest $request)
    {
        return $this->success(
            $this->service->update($request->validated()),
            Response::HTTP_OK
        );
    }

    public function delete(DummyBusinessCardRequest $request)
    {
        return $this->success(
            $this->service->delete($request->id),
            Response::HTTP_OK
        );
    }

    public function get(DummyBusinessCardRequest $request)
    {
        return $this->success(
            DummyBusinessCardResource::make(
                $this->service->get($request->id),
                Response::HTTP_OK
            )
        );
    }
}
