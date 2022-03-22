<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendPasswordRequest;
use App\Http\Requests\SetPinRequest;
use App\Http\Requests\SignInRequest;
use App\Services\SMSService;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected SMSService $SMSService
    ) {}

    public function signin(SignInRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->service->searchByPhoneNumber($request->phone_number);
        if (!$user) {
            return $this->error('Invalid login', 401);
        }

        if (is_null($user->phone_number_verified_at)) {
            return $this->error('User is not verified ', 401);
        }
        $verification_code = Random::generate(4, '0-9');


        $phone_number = str($user->phone_number)->replace('+', '')->value();

        $user->verification_code = $verification_code;
        $user->save();
        $status = $this->SMSService->sendSms("Your password: $verification_code", $phone_number);
        if (isset($status['error'])) {
            $this->error('Something went wrong', 400);
        }

        return $this->success(null, 'Provide password from SMS');
    }

    public function sendPassword(SendPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->service->searchByPhoneNumber($request->phone_number);
        if (!$user) {
            return $this->error('Invalid login', 401);
        }
        if (!$this->attempt($user, $request->pin)) {
            return $this->error('Password is invalid', 401);
        }

        return $this->success(
            $user->createToken("Token for user #$user->id")->plainTextToken,
            'Authenticated'
        );
    }

    protected function attempt(Authenticatable $user, string $code): bool
    {
        if ($user->verification_code == $code) {
            return true;
        }

        return false;
    }
}
