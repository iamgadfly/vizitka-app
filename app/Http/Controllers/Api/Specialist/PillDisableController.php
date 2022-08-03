<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PillDisable\CreateRequest;
use App\Http\Requests\PillDisable\DeleteRequest;
use App\Services\PillDisableService;
use Illuminate\Http\JsonResponse;

class PillDisableController extends Controller
{
    public function __construct(
        protected PillDisableService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws RecordIsAlreadyExistsException
     * @lrd:start
     * Disable Pill
     * @lrd:end
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated())
        );
    }

    /**
     * @param DeleteRequest $request
     * @return JsonResponse
     * @throws RecordNotFoundException
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Enable Pill
     * @lrd:end
     */
    public function delete(DeleteRequest $request)
    {
        return $this->success(
            $this->service->delete($request->time, $request->date)
        );
    }
}
