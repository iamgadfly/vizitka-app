<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\CreateShortlinkRequest;
use App\Http\Requests\Share\GetByHashRequest;
use App\Http\Resources\ShareResource;
use App\Services\ShareService;

class ShareController extends Controller
{
    public function __construct(
        protected ShareService $service
    ){}

    public function createShortlink(CreateShortlinkRequest $request)
    {
        return $this->success(
            new ShareResource($this->service->createShortlink($request->url))
        );
    }

    public function get(GetByHashRequest $request)
    {
        return $this->success(
            $this->service->getByHash($request->hash)
        );
    }
}
