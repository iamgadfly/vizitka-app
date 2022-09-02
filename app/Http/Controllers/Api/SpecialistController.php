<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\SpecialistNotCreatedException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\CardBackgroundHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Specialist\CreateSpecialistRequest;
use App\Http\Requests\Specialist\GetSpecialistRequest;
use App\Http\Resources\Specialist\FullInfoResource;
use App\Http\Resources\SpecialistDetailedDataResource;
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

    /**
     * @param CreateSpecialistRequest $request
     * @return JsonResponse
     * @lrd:start
     * Create Specialist route
     * @lrd:end
     * @throws SpecialistNotCreatedException
     */
    public function create(CreateSpecialistRequest $request)
    {
        if (!is_null($request->avatar['id'])) {
            $image = $this->imageService->get($request->avatar['id']);
            $this->imageService->removeTemporary($image); // make 'deleted_at' field null
        }

        return $this->success(
            new SpecialistResource($this->service->create($request->toArray())),
            Response::HTTP_CREATED,'Specialist created');
    }

    /**
     * @param GetSpecialistRequest $request
     * @return JsonResponse
     * @lrd:start
     * Get Specialist route
     * @lrd:end
     * @throws SpecialistNotFoundException
     */
    public function get(GetSpecialistRequest $request): JsonResponse
    {
        return $this->success(
            FullInfoResource::make($this->service->getSpecialistData($request->id)),
            Response::HTTP_OK,
        );
    }

    /**
     * @param CreateSpecialistRequest $request
     * @return JsonResponse
     * @lrd:start
     * Update Specialist route
     * @lrd:end
     * @throws SpecialistNotFoundException
     */
    public function update(CreateSpecialistRequest $request): JsonResponse
    {
        if (!is_null($request?->avatar['id']) && !is_null($this->service->getMe()?->avatar_id)) {
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

    /**
     * @return JsonResponse
     * @lrd:start
     * Get current Specialist route
     * @lrd:end
     * @throws SpecialistNotFoundException
     */
    public function me(): JsonResponse
    {
        return $this->success(
            FullInfoResource::make($this->service->getMe()),
            Response::HTTP_OK
        );
    }

    /**
     * @return JsonResponse
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Get my card
     * @lrd:end
     */
    public function getMyCard(): JsonResponse
    {
        return $this->success(
            $this->service->getMyCard()
        );
    }
}
