<?php

namespace App\Services;

use App\Exceptions\InvalidDeviceException;
use App\Exceptions\InvalidLoginException;
use App\Exceptions\SMSNotSentException;
use App\Exceptions\UserAlreadyVerifiedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserPinException;
use App\Exceptions\VerificationCodeIsntValidException;
use App\Models\Device;
use App\Models\User;
use App\Repositories\SpecialistRepository;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Nette\Utils\Random;

class AuthService
{
    public function __construct(
        protected SMSService $SMSService,
        protected UserService $service,
        protected SpecialistRepository $specialistRepository,
        protected DeviceService $deviceService
    ){}

    public function isUserExists(string $phoneNumber, string $device_id): array
    {
        $output = [];
        $user = $this->service->searchByPhoneNumber($phoneNumber);
        $device = $this->deviceService->getDevice($device_id, $user->id);
        if (!is_null($user)) {
            $output['user'] = true;
            $output['device'] = !is_null($device);
            $output['pin'] = !is_null($device->pin);
            $output['specialist'] = !is_null($user->specialist);
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
     * @throws InvalidLoginException
     * @throws GuzzleException
     * @throws SMSNotSentException
     */
    public function forget(string $phoneNumber): void
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber, false) ?? throw new InvalidLoginException;
        $verification_code = Random::generate(4, '0-9');

        try {
            $this->SMSService->sendSms("Код для сброса PIN: $verification_code", $phoneNumber);
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
    public function verification(array $data): string|bool
    {
        $phoneNumber = $data['phone_number'];
        $verificationCode = $data['verification_code'];

        $user = $this->service->searchByPhoneNumber($phoneNumber, false) ?? throw new UserNotFoundException;

        if ($user->is_verified) {
            throw new UserAlreadyVerifiedException;
        }

        if ($user->verification_code == $verificationCode) {
            $user->phone_number_verified_at = Carbon::now();
            $user->is_verified = true;
            $user->save();

            $this->deviceService->create([
                'device_id' => $data['device_id'],
                'user_id' => $user->id
            ]);

            return $user->createToken("Token for user #$user->id")->plainTextToken;
        }

        throw new VerificationCodeIsntValidException;
    }


    /**
     * @throws VerificationCodeIsntValidException
     * @throws UserNotFoundException
     */
    public function verifyNewDevice(array $data): bool
    {
        $phoneNumber = $data['phone_number'];
        $verificationCode = $data['verification_code'];

        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new UserNotFoundException;

        if ($user->verification_code == $verificationCode) {

            $this->deviceService->create([
                'device_id' => $data['device_id'],
                'user_id' => $user->id
            ]);

            return $user->tokens()->create("Token for user #$user->id")->plainTextToken;
        }

        throw new VerificationCodeIsntValidException;
    }


    /**
     * @param string $phoneNumber
     * @param string $deviceId
     * @param string|null $pin
     * @return string
     * @throws GuzzleException
     * @throws InvalidLoginException
     * @throws SMSNotSentException
     * @throws UserPinException
     * @throws InvalidDeviceException
     */
    public function signIn(string $phoneNumber, string $deviceId, ?string $pin = null): string
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new InvalidLoginException;
        $device = $this->deviceService->getDevice($deviceId, $user->id);


        if (is_null($device)) {
            $verification_code = Random::generate(4, '0-9');
            $this->SMSService->sendSms(
                "Код для входа с нового устройства: $verification_code", $user->phone_number
            );
            $user->verification_code = $verification_code;
            $user->save();
            throw new InvalidDeviceException;
        }

        if (!is_null($pin) && !$this->attempt($device, $pin)) {
            throw new UserPinException;
        }

        return $user->createToken("Token for user #$user->id")->plainTextToken;
    }


    /**
     * @throws InvalidLoginException
     * @throws InvalidDeviceException
     */
    public function setPin(array $data): bool
    {
        $user = $this->service->searchByPhoneNumber($data['phoneNumber']) ?? throw new InvalidLoginException;
        $device = $this->deviceService->getDevice($data['device_id'], $user->phone_number)
            ?? throw new InvalidDeviceException;

        return $this->deviceService->setPin($user->id, $device->device_id, $data['pin']);
    }

    /**
     * @throws InvalidLoginException
     * @throws InvalidDeviceException
     */
    public function unsetPin(array $data): bool
    {
        $user = $this->service->searchByPhoneNumber($data['phoneNumber']) ?? throw new InvalidLoginException;
        $device = $this->deviceService->getDevice($data['device_id'], $user->phone_number)
            ?? throw new InvalidDeviceException;

        return $this->deviceService->unsetPin($user->id, $device->device_id);
    }


    protected function attempt(Device $device, string $pin): bool
    {
        if ($device->pin == $pin) {
            return true;
        }

        return false;
    }
}
