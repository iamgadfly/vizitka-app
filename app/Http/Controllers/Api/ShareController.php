<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\LinkHasExpiredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Share\CreateShortlinkRequest;
use App\Http\Requests\Share\GetByHashRequest;
use App\Http\Resources\ShareResource;
use App\Services\ShareService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ShareController extends Controller
{
    public function __construct(
        protected ShareService $service
    ){}

    /**
     * @param CreateShortlinkRequest $request
     * @return JsonResponse
     * @lrd:start
     * Create shortlink for share
     * @lrd:end
     */
    public function createShortlink(CreateShortlinkRequest $request): JsonResponse
    {
        return $this->success(
            new ShareResource($this->service->createShortlink(
                $request->url, $request->sharable_type, $request->sharable_id
            )),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param GetByHashRequest $request
     * @return RedirectResponse
     * @throws LinkHasExpiredException
     * @lrd:start
     * Get by hash
     * @lrd:end
     */
    public function get(GetByHashRequest $request): RedirectResponse
    {
        return \response()->redirectTo($this->service->getByHash($request->hash));
    }

    /**
     * @param CreateShortlinkRequest $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function getQrCode(CreateShortlinkRequest $request): Application|ResponseFactory|\Illuminate\Http\Response
    {
        return $this->service->getQrCode($request->url);
    }
}
