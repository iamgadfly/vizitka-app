<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\CreateRequest;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function __construct(
        protected MailService $service
    ){}

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws SpecialistNotFoundException
     * @lrd:start
     * Create report
     * @lrd:end
     */
    public function createReport(CreateRequest $request)
    {
        return $this->success(
            $this->service->sendReportMail($request->validated()),
            Response::HTTP_CREATED
        );
    }

    /**
     * @return JsonResponse
     * @lrd:start
     * Get report reasons
     * @lrd:end
     */
    public function getReportReasons()
    {
        return $this->success(
            $this->service->getReportReasons()
        );
    }
}
