<?php

namespace App\Http\Controllers;

use App\Helpers\CardBackgroundHelper;
use App\Models\Client;
use App\Services\SMSService;

class TestController extends Controller
{
    public function test()
    {
        dd(CardBackgroundHelper::filenameFromActivityKind('barber'));
    }
}
