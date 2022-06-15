<?php

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Http\Requests\Test\DeleteUserRequest;
use App\Models\Specialist;
use App\Repositories\UserRepository;
use App\Repositories\WorkScheduleWorkRepository;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Jenssegers\Date\Date;

class TestController extends Controller
{
    public function __construct(
        protected UserRepository $repository
    ){}

    public function test()
    {

    }

    public function deleteUser(DeleteUserRequest $request)
    {
        $user = $this->repository->searchByPhoneNumber($request->phone_number);
        $user->delete();
        return response()->status(200);
    }
}
