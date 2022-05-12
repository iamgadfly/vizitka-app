<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CardBackgroundHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetSpecialistRequest;
use App\Http\Requests\Specialist\CreateSpecialistRequest;
use App\Http\Resources\SpecialistResource;
use App\Services\ImageService;
use App\Services\SpecialistService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SpecialistController extends Controller
{
    public function __construct(
        protected SpecialistService $service,
        protected ImageService $imageService
    ) {}

    public function create(CreateSpecialistRequest $request): JsonResponse
    {
        $specialist = $this->service->findByUserId($request->user_id);

        if (!is_null($specialist)) {
            return $this->error('Specialist is already existing', Response::HTTP_BAD_REQUEST);
        }

        if (!is_null($request->avatar_id)) {
            $image = $this->imageService->get($request->avatar_id);
            $this->imageService->removeTemporary($image); // make 'deleted_at' field null
        }

        $request->merge(['background_image' => CardBackgroundHelper::filenameFromActivityKind($request->background_image)]);

        return $this->success(
            new SpecialistResource($this->service->create($request->toArray())),
            Response::HTTP_CREATED,'Specialist created');
    }

    public function get(GetSpecialistRequest $request): JsonResponse
    {
        return $this->success(
            SpecialistResource::make($this->service->getSpecialistData($request->id)),
            Response::HTTP_OK,
        );
    }

    public function update(CreateSpecialistRequest $request): JsonResponse
    {
        if (!is_null($request?->avatar_id) && !is_null($this->service->getMe()?->avatar_id)) {
            $image = $this->imageService->get(
                $this->service->getMe()->avatar_id
            );
            $this->imageService->makeTemporary($image);
        }

        return $this->success(
            $this->service->update($request->validated()),
            Response::HTTP_OK
        );
    }

    public function me(): JsonResponse
    {
        return $this->success(
            SpecialistResource::make($this->service->getMe()),
            Response::HTTP_OK
        );
    }
}
