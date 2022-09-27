<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidDeviceException;
use App\Exceptions\InvalidLoginException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\UserAlreadyVerifiedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserPinException;
use App\Exceptions\VerificationCodeIsntValidException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\UnsetPinRequest;
use App\Http\Requests\User\IsUserExistsRequest;
use App\Http\Requests\User\LogoutRequest;
use App\Http\Requests\User\SetPinRequest;
use App\Http\Requests\User\SignInRequest;
use App\Http\Requests\User\SignUpRequest;
use App\Http\Requests\User\VerificationRequest;
use App\Services\AuthService;
use App\Services\DeviceService;
use App\Services\SMSService;
use App\Services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function auth;

class AuthController extends Controller
{
    //TODO смотри setFace
    public function __construct(
        protected UserService $service,
        protected AuthService $authService,
        protected SMSService $SMSService,
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
            $this->authService->isUserExists($request->phone_number, $request->device_id),
            Response::HTTP_OK
        );
    }

    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws InvalidLoginException
     * @throws SMSNotSentException
     */
    public function forgetPin(SignUpRequest $request)
    {
        return $this->success(
            $this->authService->forget($request->phone_number)
        );
    }

    /**
     * @param VerificationRequest $request
     * @return JsonResponse
     * @throws UserAlreadyVerifiedException
     * @throws UserNotFoundException
     * @throws VerificationCodeIsntValidException
     * @lrd:start
     * Verification route
     * @lrd:end
     */
    public function verification(VerificationRequest $request): JsonResponse
    {
        $token = $this->authService->verification($request->validated());

        return $this->success(
            $token,
            Response::HTTP_OK,
            'User is verified',
        );
    }

    /**
     * @param LogoutRequest $request
     * @return JsonResponse
     * @throws InvalidDeviceException
     * @throws InvalidLoginException
     * @lrd:start
     * Logout route
     * @lrd:end
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        return $this->success($this->authService->logout(
            $request->validated()
        ));
    }

    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws SMSNotSentException
     * @lrd:start
     * Sign Up route
     * @lrd:end
     */
    public function signup(SignUpRequest $request): JsonResponse
    {
        $user = $this->service->create($request->validated());

        $this->SMSService->sendSms("$user->verification_code - код для входа в приложение Визитка.", $request->phone_number);

        return $this->success([
            'code' => $user->verification_code
        ], Response::HTTP_CREATED ,'Verification code sent');
    }

    /**
     * @param SignInRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws InvalidDeviceException
     * @throws InvalidLoginException
     * @throws SMSNotSentException
     * @throws UserPinException
     * @lrd:start
     * Sign In
     * @lrd:end
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        return $this->success(
            $this->authService->signIn($request->phone_number, $request->device_id, $request->pin)
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws InvalidDeviceException
     */
    public function setPin(SetPinRequest $request)
    {
        return $this->success(
            $this->authService->setPin($request->validated())
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws InvalidDeviceException
     */
    public function unsetPin(UnsetPinRequest $request)
    {
        return $this->success(
            $this->authService->unsetPin($request->validated())
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws InvalidDeviceException
     */
    public function setFace(UnsetPinRequest $request, AuthService $authService): JsonResponse
    {
        return $this->success(
            $authService->setFace($request->validated())
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws InvalidDeviceException
     */
    public function unsetFace(UnsetPinRequest $request, AuthService $authService): JsonResponse
    {
        return $this->success(
            $authService->unsetFace($request->validated())
        );
    }

    /**
     * @throws InvalidLoginException
     * @throws GuzzleException
     * @throws SMSNotSentException
     */
    public function resendSms(SignUpRequest $request)
    {
        return $this->success(
            $this->authService->resendSms($request->phone_number)
        );
    }
}
