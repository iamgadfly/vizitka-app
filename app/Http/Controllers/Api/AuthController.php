<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\SMSNotSentException;
use App\Exceptions\UserAlreadyVerifiedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\VerificationCodeIsntValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\VerificationRequest;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\UserService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Nette\Utils\Random;
use Symfony\Component\HttpFoundation\Response;
use function auth;
use function str;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected AuthService $authService,
        protected SMSService $SMSService
    ) {}

    /**
     * @throws UserAlreadyVerifiedException
     * @throws VerificationCodeIsntValidException
     * @throws UserNotFoundException
     */
    public function verification(VerificationRequest $request): JsonResponse
    {
        $token = $this->authService->verification($request->phone_number, $request->verification_code);

        return $this->success(
            $token,
            \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            'User is verified',
        );
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success(null, 'Tokens revoked', Response::HTTP_OK);
    }

    /**
     * @throws SMSNotSentException
     * @throws GuzzleException
     */
    public function signup(SignUpRequest $request): JsonResponse
    {
        $user = $this->service->create($request->validated());

        $this->SMSService->sendSms("Your verification code: $user->verification_code", $request->phone_number);

        return $this->success(null, Response::HTTP_CREATED ,'Verification code sent');
    }
}
