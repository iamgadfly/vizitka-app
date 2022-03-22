<?php

namespace App\Http\Controllers;

use App\Services\SMSService;

class TestController extends Controller
{
    public function test()
    {
        $sms = new SMSService();
        return $sms->sendSms('Test', '79654605102');
    }
}
