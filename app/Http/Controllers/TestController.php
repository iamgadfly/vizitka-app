<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Helpers\TimeHelper;
use App\Http\Requests\Test\DeleteUserRequest;
use App\Repositories\SpecialistRepository;
use App\Repositories\UserRepository;
use App\Services\GeocodeService;
use App\Services\QRService;

class TestController extends Controller
{
    public function __construct(
        protected UserRepository $repository,
        protected SpecialistRepository $specialistRepository
    ){}

    /**
     * @throws \Geocoder\Exception\Exception
     */
    public function test()
    {
        dd(GeocodeService::fromAddress('Япония, Токио, Синдзюку'));
    }

    public function deleteUser(DeleteUserRequest $request)
    {
        if ($request->as_specialist) {
            $user = $this->specialistRepository->findByPhoneNumber($request->phone_number);
        } else {
            $user = $this->repository->searchByPhoneNumber($request->phone_number);
        }
        $user->delete();
        return response()->status(200);
    }
}
