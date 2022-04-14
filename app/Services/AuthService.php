<?php

namespace App\Services;

use App\Exceptions\InvalidLoginException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\TooManyLoginAttemptsException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserAlreadyVerifiedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserNotVerifiedException;
use App\Exceptions\UserPinException;
use App\Exceptions\VerificationCodeIsntValidException;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Nette\Utils\Random;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthService
{
    public function __construct(
        protected SMSService $SMSService,
        protected UserService $service
    ){}

    public function isUserExists(string $phoneNumber)
    {
        try {
            $user = $this->service->searchByPhoneNumber($phoneNumber);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @throws UserNotFoundException
     * @throws UserAlreadyVerifiedException
     * @throws VerificationCodeIsntValidException
     */
    public function verification(string $phoneNumber, string $verificationCode): string
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new UserNotFoundException;

        if (!is_null($user->phone_number_verified_at)) {
            throw new UserAlreadyVerifiedException;
        }

        if ($user->verification_code == $verificationCode) {
            $user->phone_number_verified_at = Carbon::now();
            $user->save();

            return $user->createToken("Token for user #$user->id")->plainTextToken;
        }

        throw new VerificationCodeIsntValidException;
    }

    /**
     * @throws SMSNotSentException
     * @throws TooManyLoginAttemptsException
     * @throws GuzzleException
     */
    public function sendSmsPassword(string $phoneNumber)
    {
        $user = $this->service->searchByPhoneNumberNotNull($phoneNumber);
        if (\Cache::get("User#$user->id") > 10) {
            throw new TooManyLoginAttemptsException;
        }
        $verification_code = Random::generate(4, '0-9');

        $phone_number = str($user->phone_number)->replace('+', '')->value();
        if (\Cache::has("User#$user->id")) {
            \Cache::increment("User#$user->id");
        } else {
            \Cache::put("User#$user->id", 1, 15 * 60);
        }
        try {
            $this->SMSService->sendSms("Ваш пароль: $verification_code", $phone_number);
        } catch (SMSNotSentException $e) {
            throw new SMSNotSentException;
        }

        $user->verification_code = $verification_code;
        $user->save();
    }

    /**
     * @throws InvalidLoginException
     * @throws UserNotVerifiedException
     * @throws InvalidPasswordException
     */
    public function clientSignIn(string $phoneNumber, string $password)
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;

        if (is_null($user->phone_number_verified_at)) {
            throw new UserNotVerifiedException;
        }
        if (!$this->clientAttempt($user, $password)) {
            throw new InvalidPasswordException;
        }

        return $user->createToken("Token for user #$user->id")->plainTextToken;
    }

    /**
     * @throws UnauthorizedException
     */
    public function specialistSetPin(?User $user, string $pin)
    {
        $user ?? throw new UnauthorizedException;

        $user->pin = $pin;
        $user->save();
    }

    /**
     * @throws InvalidLoginException
     * @throws UserNotVerifiedException
     * @throws UserPinException
     */
    public function specialistSignIn(string $phoneNumber, string $pin)
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;

        if (is_null($user->phone_number_verified_at)) {
            throw new UserNotVerifiedException;
        }

        if (!$this->specialistAttempt($user, $pin)) {
            throw new UserPinException;
        }

        return $user->createToken("Token for user #$user->id")->plainTextToken;
    }

    protected function specialistAttempt(Authenticatable $user, string $pin): bool
    {
        if ($user->pin == $pin) {
            return true;
        }

        return false;
    }

    protected function clientAttempt(Authenticatable $user, string $code): bool
    {
        if ($user->verification_code == $code) {
            \Cache::delete("User#$user-id");
            return true;
        }
        return false;
    }
}
