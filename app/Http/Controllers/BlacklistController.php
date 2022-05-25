<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blacklist\CreateRequest;
use App\Http\Requests\Blacklist\DeleteRequest;
use App\Services\BlacklistService;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function __construct(
        protected BlacklistService $service
    ){}

    public function create(CreateRequest $request)
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    public function delete(DeleteRequest $request)
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }
}
