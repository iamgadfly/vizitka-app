<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Http\Requests\VerificationRequest;
use App\Services\SMSService;
use App\Services\UserService;
use Carbon\Carbon;
use Nette\Utils\Random;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected SMSService $SMSService
    ) {}

    public function verification(VerificationRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->service->searchByPhoneNumber($request->phone_number);

        if (is_null($user)) {
            return $this->error('User not found', Response::HTTP_NOT_FOUND);
        }

        if (!is_null($user->phone_number_verified_at)) {
            return $this->error('User has already been verified', Response::HTTP_BAD_REQUEST);
        }

        if ($user->verification_code == $request->verification_code) {
            $user->phone_number_verified_at = Carbon::now();
            $user->save();

            return $this->success(
                $user->createToken("Token for user #$user->id")->plainTextToken,
                'User is verified',
                Response::HTTP_OK
            );
        }

        return $this->error('Verification code is not valid', Response::HTTP_BAD_REQUEST);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success(null, 'Tokens revoked', Response::HTTP_OK);
    }

    public function signup(SignUpRequest $request): \Illuminate\Http\JsonResponse
    {
        $verification_code = Random::generate(4, '0-9');

        $phone_number = str($request->phone_number)->replace('+', '')->value();

        $user = $this->service->create($request->validated());

        $user->verification_code = $verification_code;
        $user->save();

        $status = $this->SMSService->sendSms("Your verification code: $verification_code", $phone_number);
        if (isset($status['sms'][0]['error'])) {
            $this->error('Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->success(null, 'Verification code sent', Response::HTTP_CREATED);
    }
}
