<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\Support\CreateRequest;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct(
        protected MailService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @lrd:start
     * Send Mail To Support As Specialist
     * @lrd:end
     */
    public function createSupportAsSpecialist(CreateRequest $request)
    {
        return $this->success(
            $this->service->sendMailToSupportAsSpecialist($request->validated(), $request->file)
        );
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @lrd:start
     * Send Mail To Support As Client
     * @lrd:end
     */
    public function createSupportAsClient(CreateRequest $request)
    {
        return $this->success(
            $this->service->sendMailToSupportAsClient($request->validated(), $request->file)
        );
    }
}
