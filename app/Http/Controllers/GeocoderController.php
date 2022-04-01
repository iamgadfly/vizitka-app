<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeocodeRequest;
use App\Services\GeocoderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GeocoderController extends Controller
{
    public function __construct(
        protected GeocoderService $service
    ) {}

    public function geocode(GeocodeRequest $request)
    {
        $coords = explode(',', $request->coordinates);
        return $this->success(
            $this->service->fromCoordinates(
                $coords[0], $coords[1]
            ),
            Response::HTTP_OK
        );
    }
}
