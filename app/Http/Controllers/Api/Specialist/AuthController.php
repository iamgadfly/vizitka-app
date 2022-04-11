<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\InvalidLoginException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserNotVerifiedException;
use App\Exceptions\UserPinException;
use App\Http\Controllers\Api\AuthController as BaseAuthController;
use App\Http\Requests\SetPinRequest;
use App\Http\Requests\SignInRequest;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use function auth;

class AuthController extends BaseAuthController
{
    public function __construct(
        protected UserService $service,
        protected AuthService $authService,
        protected SMSService $SMSService
    ) {}

    /**
     * @throws UserPinException
     * @throws InvalidLoginException
     * @throws UserNotVerifiedException
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        $token = $this->authService->specialistSignIn($request->phone_number, $request->pin);
        return $this->success(
            $token,
            Response::HTTP_OK,
            'Authenticated'
        );
    }

    /**
     * @throws UnauthorizedException
     */
    public function setPin(SetPinRequest $request)
    {
        $this->authService->specialistSetPin(auth()->user(), $request->pin);

        return $this->success(
            null,
            Response::HTTP_OK,
            'PIN set successfully'
        );
    }
}
