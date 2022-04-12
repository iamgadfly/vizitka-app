<?php

namespace App\Http\Controllers;

use App\Helpers\CardBackgroundHelper;
use App\Helpers\ImageHelper;
use App\Models\Client;
use App\Models\Image;
use App\Models\Specialist;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        return ImageHelper::getAssetFromFilename('images/card_backgrounds/test.svg');
    }
}
