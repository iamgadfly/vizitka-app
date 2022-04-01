<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Requests\SetPinRequest;
use App\Http\Requests\SignInRequest;
use App\Services\SMSService;
use App\Services\UserService;
use App\Http\Controllers\AuthController as BaseAuthController;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;

class AuthController extends BaseAuthController
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

        if (!$this->attempt($user, $request->pin)) {
            return $this->error('PIN is invalid', 401);
        }

        return $this->success(
            $user->createToken("Token for user #$user->id")->plainTextToken,
            Response::HTTP_OK,
            'Authenticated'
        );
    }

    public function setPin(SetPinRequest $request)
    {
        $user = auth()->user();
        if (is_null($user)) {
            return $this->error('Unauthorized', 401);
        }
        $user->pin = $request->pin;
        $user->save();

        return $this->success(
            null,
            Response::HTTP_OK,
            'PIN set successfully'
        );
    }

    protected function attempt(Authenticatable $user, string $pin): bool
    {
        if ($user->pin == $pin) {
            return true;
        }

        return false;
    }
}
