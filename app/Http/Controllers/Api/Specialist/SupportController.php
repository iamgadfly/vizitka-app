<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\Support\CreateRequest;
use App\Services\MailService;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct(
        protected MailService $service
    ){}

    public function createSupport(CreateRequest $request)
    {
        return $this->success(
            $this->service->sendMailToSupportAsSpecialist($request->validated(), $request->file)
        );
    }
}
