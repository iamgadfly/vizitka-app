<?php

namespace App\Http\Controllers\Api\Specialist;

use App\Exceptions\InvalidLoginException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserPinException;
use App\Exceptions\VerificationCodeIsntValidException;
use App\Http\Controllers\Api\AuthController as BaseAuthController;
use App\Http\Requests\User\PinResetRequest;
use App\Http\Requests\User\SendPinResetRequest;
use App\Http\Requests\User\SetPinRequest;
use App\Http\Requests\User\SignInRequest;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
    public function setPin(SetPinRequest $request): JsonResponse
    {
        $this->authService->specialistSetPin(auth()->user(), $request->pin);

        return $this->success(
            null,
            Response::HTTP_OK,
            'PIN set successfully'
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws GuzzleException
     * @throws SMSNotSentException
     */
    public function sendPinResetRequest(SendPinResetRequest $request): JsonResponse
    {
        return $this->success(
            $this->authService->sendPinResetRequest($request->phone_number),
            Response::HTTP_OK
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws VerificationCodeIsntValidException
     */
    public function pinReset(PinResetRequest $request): JsonResponse
    {
        return $this->success(
            $this->authService->pinReset(
                $request->phone_number,
                $request->verification_code,
                $request->pin
            )
        );
    }
}
