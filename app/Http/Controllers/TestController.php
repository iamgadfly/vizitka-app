<?php

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Models\Specialist;
use App\Repositories\WorkScheduleWorkRepository;
use App\Services\AppointmentService;
use Carbon\Carbon;

class TestController extends Controller
{
    public function test()
    {
        $phoneNumber = "+79000000000";
        dd(Specialist::whereHas('user', function ($q) use ($phoneNumber) {
            return $q->where('phone_number', $phoneNumber);
        })->get()->first());
    }
}
