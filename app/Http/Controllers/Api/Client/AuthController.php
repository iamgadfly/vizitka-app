<?php

namespace App\Http\Controllers\Api\Client;

use App\Exceptions\InvalidLoginException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\TooManyLoginAttemptsException;
use App\Exceptions\UserNotVerifiedException;
use App\Http\Controllers\Api\AuthController as BaseAuthController;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendPasswordRequest;
use App\Http\Requests\SignInRequest;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Nette\Utils\Random;
use function str;

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
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        $this->authService->sendSmsPassword($request->phone_number);

        return $this->success(null,Response::HTTP_OK, 'Provide password from SMS');
    }

    /**
     * @throws InvalidLoginException
     * @throws UserNotVerifiedException
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
