<?php

namespace App\Services;

use App\Exceptions\InvalidLoginException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\TooManyLoginAttemptsException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserAlreadyVerifiedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserPinException;
use App\Exceptions\VerificationCodeIsntValidException;
use App\Models\User;
use App\Repositories\SpecialistRepository;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Auth\Authenticatable;
use Nette\Utils\Random;

class AuthService
{
    public function __construct(
        protected SMSService $SMSService,
        protected UserService $service,
        protected SpecialistRepository $specialistRepository
    ){}

    public function isUserExists(string $phoneNumber): array
    {
        $output = [];
        $user = $this->service->searchByPhoneNumber($phoneNumber);
        if (!is_null($user)) {
            $output['user'] = true;
            $output['specialist'] = !is_null($this->specialistRepository->findByPhoneNumber($phoneNumber));
            $output['client'] = !is_null($user->client);
        } else {
            $output['user'] = false;
        }
        return $output;
    }

    /**
     * @throws GuzzleException
     * @throws SMSNotSentException
     * @throws InvalidLoginException
     */
    public function resendSms(string $phoneNumber): void
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber, false) ?? throw new InvalidLoginException;

        $verification_code = Random::generate(4, '0-9');

        try {
            $this->SMSService->sendSms("Код верификации: $verification_code", $phoneNumber);
        } catch (SMSNotSentException $e) {
            throw new SMSNotSentException;
        }

        $user->verification_code = $verification_code;
        $user->save();
    }

    /**
     * @throws UserNotFoundException
     * @throws UserAlreadyVerifiedException
     * @throws VerificationCodeIsntValidException
     */
    public function verification(string $phoneNumber, string $verificationCode): string
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber, false) ?? throw new UserNotFoundException;

        if ($user->is_verified) {
            throw new UserAlreadyVerifiedException;
        }

        if ($user->verification_code == $verificationCode) {
            $user->phone_number_verified_at = Carbon::now();
            $user->is_verified = true;
            $user->save();

            return $user->createToken("Token for user #$user->id")->plainTextToken;
        }

        throw new VerificationCodeIsntValidException;
    }

    /**
     * @throws SMSNotSentException
     * @throws TooManyLoginAttemptsException
     * @throws GuzzleException
     * @throws InvalidLoginException
     */
    public function sendSmsPassword(string $phoneNumber): void
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;
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
     * @throws InvalidPasswordException
     */
    public function clientSignIn(string $phoneNumber, string $password): string
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;

        if (!$this->clientAttempt($user, $password)) {
            throw new InvalidPasswordException;
        }

        return $user->createToken("Token for user #$user->id")->plainTextToken;
    }

    /**
     * @throws UnauthorizedException
     */
    public function specialistSetPin(?User $user, string $pin): void
    {
        $user ?? throw new UnauthorizedException;

        $user->pin = $pin;
        $user->save();
    }

    /**
     * @throws InvalidLoginException
     * @throws UserPinException
     */
    public function specialistSignIn(string $phoneNumber, string $pin): string
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;

        if (!$this->specialistAttempt($user, $pin)) {
            throw new UserPinException;
        }

        return $user->createToken("Token for user #$user->id")->plainTextToken;
    }

    /**
     * @throws InvalidLoginException
     * @throws SMSNotSentException
     * @throws GuzzleException
     */
    public function sendPinResetRequest(string $phoneNumber): array
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;

        $code = Random::generate(4, '0-9');
        try {
            $this->SMSService->sendSms("Код для сброса PIN: $code", $phoneNumber);
        } catch (SMSNotSentException $e) {
            throw new SMSNotSentException;
        }

        $user->verification_code = $code;
        $user->save();

        return ['code' => $code];
    }

    /**
     * @throws InvalidLoginException
     * @throws VerificationCodeIsntValidException
     */
    public function pinReset(string $phoneNumber, string $verificationCode, string $pin): bool
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;

        if ($user->verification_code !== $verificationCode) {
            throw new VerificationCodeIsntValidException;
        }

        $user->pin = $pin;
        $user->save();

        return true;
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
