<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidLoginException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\UserAlreadyVerifiedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\VerificationCodeIsntValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IsUserExistsRequest;
use App\Http\Requests\User\SignUpRequest;
use App\Http\Requests\User\VerificationRequest;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function auth;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected AuthService $authService,
        protected SMSService $SMSService
    ) {}

    /**
     * @param IsUserExistsRequest $request
     * @return JsonResponse
     * @lrd:start
     * Is User Exists route
     * @lrd:end
     */
    public function isUserExists(IsUserExistsRequest $request): JsonResponse
    {
        return $this->success(
            $this->authService->isUserExists($request->phone_number),
            Response::HTTP_OK
        );
    }

    /**
     * @throws UserAlreadyVerifiedException
     * @throws VerificationCodeIsntValidException
     * @throws UserNotFoundException
     * @lrd:start
     * Verification route
     * @lrd:end
     */
    public function verification(VerificationRequest $request): JsonResponse
    {
        $token = $this->authService->verification($request->phone_number, $request->verification_code);

        return $this->success(
            $token,
            Response::HTTP_OK,
            'User is verified',
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws GuzzleException
     * @throws SMSNotSentException
     * @lrd:start
     * Resend SMS route
     * @lrd:end
     */
    public function resendSms(SignUpRequest $request): JsonResponse
    {
        $this->authService->resendSms($request->phone_number);
        return $this->success(
            null,
            Response::HTTP_OK,
            'SMS was sent'
        );
    }

    /**
     * @return JsonResponse
     * @lrd:start
     * Logout route
     * @lrd:end
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return $this->success(null, 'Tokens revoked', Response::HTTP_OK);
    }

    /**
     * @throws SMSNotSentException
     * @throws GuzzleException
     * @lrd:start
     * Sign Up route
     * @lrd:end
     */
    public function signup(SignUpRequest $request): JsonResponse
    {
        $user = $this->service->create($request->validated());

        $this->SMSService->sendSms("Код верификации: $user->verification_code", $request->phone_number);

        return $this->success([
            'code' => $user->verification_code
        ], Response::HTTP_CREATED ,'Verification code sent');
    }
}