<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendPasswordRequest;
use App\Http\Requests\SignInRequest;
use App\Services\SMSService;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Nette\Utils\Random;
use function str;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected SMSService $SMSService
    ) {}

    public function signin(SignInRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->service->searchByPhoneNumberNotNull($request->phone_number);

        $verification_code = Random::generate(4, '0-9');


        $phone_number = str($user->phone_number)->replace('+', '')->value();

        $status = $this->SMSService->sendSms("Your password: $verification_code", $phone_number);
        if (isset($status['error'])) {
            $this->error('Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $user->verification_code = $verification_code;
        $user->save();

        return $this->success(null,Response::HTTP_OK, 'Provide password from SMS');
    }

    public function sendPassword(SendPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->service->searchByPhoneNumber($request->phone_number);
        if (!$user) {
            return $this->error('Invalid login', Response::HTTP_UNAUTHORIZED);
        }
        if (is_null($user->phone_number_verified_at)) {
            return $this->error('User is not verified ', Response::HTTP_UNAUTHORIZED);
        }
        if (!$this->attempt($user, $request->pin)) {
            return $this->error('Password is invalid', Response::HTTP_UNAUTHORIZED);
        }

        return $this->success(
            $user->createToken("Token for user #$user->id")->plainTextToken,
            Response::HTTP_OK,
            'Authenticated'
        );
    }

    protected function attempt(Authenticatable $user, string $code): bool
    {
        if ($user->verification_code == $code) {
            return true;
        }

        return false;
    }
}
