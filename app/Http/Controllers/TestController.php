<?php

namespace App\Http\Controllers;

use App\Helpers\CardBackgroundHelper;
use App\Models\Client;
use App\Models\Specialist;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        try {
            dd(Specialist::all()->toArray());
            DB::beginTransaction();
            $user = new User();
            $user->phone_number = 'c';
            $user->save();
            $specialist = new Specialist();
            $specialist->user_id = $user->id;
            $specialist->name = 'a';
            $specialist->surname = 'b';
            $specialist->activity_kind_id = 0;
            $specialist->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
