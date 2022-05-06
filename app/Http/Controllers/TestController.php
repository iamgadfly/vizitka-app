<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function test()
    {
        \Artisan::call('migrate:refresh --seed');
    }
}
