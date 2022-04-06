<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MiscService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function __construct(
        protected MiscService $service
    ){}

    public function getCountries()
    {
        return $this->success(
            $this->service->getCountries()
        );
    }
}
