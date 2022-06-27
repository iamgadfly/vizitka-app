<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\SpecialistNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\CreateRequest;
use App\Services\MailService;

class ReportController extends Controller
{
    public function __construct(
        protected MailService $service
    ){}

    /**
     * @throws SpecialistNotFoundException
     */
    public function createReport(CreateRequest $request)
    {
        return $this->success(
            $this->service->sendReportMail($request->validated())
        );
    }

    public function getReportReasons()
    {
        return $this->success(
            $this->service->getReportReasons()
        );
    }
}
