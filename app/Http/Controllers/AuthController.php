<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\CreateSpecialistRequest;
use App\Http\Requests\SendVerificationCodeRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\VerificationRequest;
use App\Models\User;
use App\Services\SMSService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected SMSService $SMSService
    ) {}

    public function signin(SignInRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->attempt(
            $request->phone_number,
            $request->password
        );
        if (!$user) {
            return $this->error('Invalid login or password', 401);
        }

        if (is_null($user->phone_number_verified_at)) {
            return $this->error('User is not verified ', 401);
        }

        return $this->success(
            $user->createToken("Token for user #$user->id")->plainTextToken,
            'Authenticated'
        );
    }

    public function sendVerificationCode(SendVerificationCodeRequest $request): \Illuminate\Http\JsonResponse
    {
        $verification_code = Random::generate(4, '0-9');

        $user = $this->service->searchByPhoneNumber($request->phone_number);

        if (is_null($user)) {
            return $this->error('User not found', 404);
        }

        if (!is_null($user->phone_number_verified_at)) {
            return $this->error('User has already been verified', 400);
        }

        $phone_number = str($user->phone_number)->replace('+', '')->value();

        $user->verification_code = $verification_code;
        $user->save();
        $status = $this->SMSService->sendSms("Your verification code: $verification_code", $phone_number);
        if (isset($status['error'])) {
            $this->error('Something went wrong', 400);
        }

        return $this->success(null, 'Verification code sent');
    }

    public function verification(VerificationRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->service->searchByPhoneNumber($request->phone_number);

        if (is_null($user)) {
            return $this->error('User not found', 404);
        }

        if (!is_null($user->phone_number_verified_at)) {
            return $this->error('User has already been verified', 400);
        }

        if ($user->verification_code == $request->verification_code) {
            $user->phone_number_verified_at = Carbon::now();
            $user->save();

            return $this->success(null, 'User is verified');
        }

        return $this->error('Verification code is not valid', 400);
    }

    public function signup(SignUpRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->success(
            $this->service->create($request->validated()),
            'User created',
            201
        );
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success(null, 'Tokens revoked');
    }

    protected function attempt(string $number, string $password): ?User
    {
        $user = $this->service->searchByPhoneNumber($number);
        if (Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }
}
