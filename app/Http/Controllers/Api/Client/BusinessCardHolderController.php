<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessCardHolderRequest;
use App\Services\BusinessCardHolderService;
use Illuminate\Http\Request;

class BusinessCardHolderController extends Controller
{
    public function __construct(
        BusinessCardHolderService $service
    ) {}

    public function create(BusinessCardHolderRequest $request)
    {

    }
}
