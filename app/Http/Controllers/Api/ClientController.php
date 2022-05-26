<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\GetClientRequest;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $service,
        protected ImageService $imageService
    ) {}

    public function create(CreateClientRequest $request): JsonResponse
    {
        $client = $this->service->findByUserId($request->user_id);

        if (!is_null($client)) {
            return $this->error('Client is already existing', Response::HTTP_BAD_REQUEST);
        }

        if (!is_null($request->avatar_id)) {
            $image = $this->imageService->get($request->avatar_id);
            $this->imageService->removeTemporary($image); // make 'deleted_at' field null
        }

        return $this->success(
            ClientResource::make($this->service->create($request->toArray())),
            Response::HTTP_CREATED ,
            'Client created'
        );
    }

    public function update(CreateClientRequest $request): JsonResponse
    {
        if (!is_null($request?->avatar_id) && !is_null($this->service->getMe()?->avatar_id)) {
            $image = $this->imageService->get(
                $this->service->getMe()->avatar_id
            );
            $this->imageService->makeTemporary($image);
        }

        return $this->success(
            $this->service->update($request->validated())
        );
    }

    public function get(GetClientRequest $request): JsonResponse
    {
        return $this->success(
            ClientResource::make($this->service->getClientData($request->id)),
            Response::HTTP_OK,
        );
    }

    public function me(): JsonResponse
    {
        return $this->success(
            ClientResource::make($this->service->getMe()),
            Response::HTTP_OK,
        );
    }
}
