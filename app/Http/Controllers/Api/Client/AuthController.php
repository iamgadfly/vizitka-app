<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\InvalidLoginException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\TooManyLoginAttemptsException;
use App\Http\Controllers\Api\AuthController as BaseAuthController;
use App\Http\Requests\SendPasswordRequest;
use App\Http\Requests\SignInRequest;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends BaseAuthController
{
    public function __construct(
        protected UserService $service,
        protected AuthService $authService,
        protected SMSService $SMSService
    ) {}

    /**
     * @throws TooManyLoginAttemptsException
     * @throws SMSNotSentException
     * @throws GuzzleException
     * @throws InvalidLoginException
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        $this->authService->sendSmsPassword($request->phone_number);

        return $this->success(null,Response::HTTP_OK, 'Provide password from SMS');
    }

    /**
     * @throws InvalidLoginException
     * @throws InvalidPasswordException
     */
    public function sendPassword(SendPasswordRequest $request): JsonResponse
    {
        $token = $this->authService->clientSignIn($request->phone_number, $request->pin);

        return $this->success(
            $token,
            Response::HTTP_OK,
            'Authenticated'
        );
    }
}
