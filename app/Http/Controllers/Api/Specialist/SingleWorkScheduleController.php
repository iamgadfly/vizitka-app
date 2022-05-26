<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\SingleWorkSchedule\CreateBreakRequest;
use App\Http\Requests\SingleWorkSchedule\CreateRequest;
use App\Http\Requests\SingleWorkSchedule\DeleteRequest;
use App\Http\Resources\SingleWorkSchueduleResource;
use App\Services\SingleWorkScheduleService;
use Illuminate\Http\JsonResponse;

class SingleWorkScheduleController extends Controller
{

    public function __construct(
        protected SingleWorkScheduleService $service
    ){}

    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            SingleWorkSchueduleResource::collection($this->service->create($request->validated()))
        );
    }

    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    public function createBreak(CreateBreakRequest $request): JsonResponse
    {
        return $this->success(
            SingleWorkSchueduleResource::make($this->service->createBreak($request->validated()))
        );
    }
}
