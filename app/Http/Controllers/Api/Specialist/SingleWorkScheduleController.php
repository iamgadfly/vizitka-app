<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\BaseException;
use App\Exceptions\RecordIsAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SingleWorkSchedule\CreateBreakRequest;
use App\Http\Requests\SingleWorkSchedule\CreateRequest;
use App\Http\Requests\SingleWorkSchedule\CreateWorkdayRequest;
use App\Http\Requests\SingleWorkSchedule\DeleteRequest;
use App\Http\Resources\SingleWorkSchueduleResource;
use App\Services\SingleWorkScheduleService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SingleWorkScheduleController extends Controller
{

    public function __construct(
        protected SingleWorkScheduleService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @lrd:start
     * Create Single Work Schedule route
     * @lrd:end
     * @throws BaseException
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param CreateWorkdayRequest $request
     * @return JsonResponse
     * @lrd:start
     * Create Single Workday route
     * @lrd:end
     */
    public function createWorkday(CreateWorkdayRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->createWorkday($request->validated()),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     * @lrd:start
     * Delete Single Work Schedule route
     * @lrd:end
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->delete($request->id)
        );
    }

    /**
     * @param CreateBreakRequest $request
     * @return JsonResponse
     * @lrd:start
     * Create Break for a Single Work Schedule route
     * @lrd:end
     * @throws RecordIsAlreadyExistsException
     */
    public function createBreak(CreateBreakRequest $request): JsonResponse
    {
        return $this->success(
            SingleWorkSchueduleResource::make($this->service->createBreak($request->validated())),
            Response::HTTP_CREATED
        );
    }
}
