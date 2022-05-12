<?php

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Repositories\WorkScheduleWorkRepository;
use Carbon\Carbon;

class TestController extends Controller
{
    public function test()
    {
//        \Artisan::call('migrate:refresh --seed');
//        $dates = [];
//        foreach (range(0, 6) as $day) {
//            $dates[] = Carbon::now()->startOfWeek()->addDay($day)->format('d.m.Y');
//        }
//        dd($dates);

//        dd(TimeHelper::getTimeInterval("13:00", "14:00"), TimeHelper::getWeekdays("15.05.2022"));
        dd(Carbon::parse('12.05.2022' . '12:22')->toISOString());
    }
}
