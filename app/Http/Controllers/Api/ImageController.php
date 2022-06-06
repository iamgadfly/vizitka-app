<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\DeleteImageRequest;
use App\Http\Requests\Image\GetImageRequest;
use App\Http\Requests\Image\UploadImageRequest;
use App\Http\Resources\ImageResource;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function __construct(
        protected ImageService $service
    ) {}

    /**
     * @param UploadImageRequest $request
     * @return JsonResponse
     * @lrd:start
     * Upload Image route
     * @lrd:end
     */
    public function upload(UploadImageRequest $request): JsonResponse
    {
        return $this->success(
            new ImageResource($this->service->create($request->image)),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param DeleteImageRequest $request
     * @return JsonResponse
     * @lrd:start
     * Delete Image route
     * @lrd:end
     */
    public function delete(DeleteImageRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    /**
     * @param GetImageRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Image route
     * @lrd:end
     */
    public function get(GetImageRequest $request): JsonResponse
    {
        return $this->success(
            ImageResource::make($this->service->get($request->id))
        );
    }
}
