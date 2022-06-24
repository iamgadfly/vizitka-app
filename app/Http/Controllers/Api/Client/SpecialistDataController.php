<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialistData\FreeHoursRequest;
use App\Http\Requests\SpecialistData\IdRequest;
use App\Services\SpecialistDataService;
use Illuminate\Http\Request;

class SpecialistDataController extends Controller
{
    public function __construct(
        protected SpecialistDataService $service
    ){}

    public function getFreeHours(FreeHoursRequest $request)
    {
        return $this->success(
            $this->service->getFreeHours($request->id, $request->date)
        );
    }

    public function getMaintenances(IdRequest $request)
    {
        return $this->success(
            $this->service->getSpecialistsMaintenances($request->id)
        );
    }
}
