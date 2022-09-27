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

    /**
     * @param string $phoneNumber
     * @param string $device_id
     * @return array
     */
    public function isUserExists(string $phoneNumber, string $device_id): array
    {
        $output = [];
        $user = $this->service->searchByPhoneNumber($phoneNumber);
        if (!is_null($user)) {
            $device = $this->deviceService->getDevice($device_id, $user->id);
            $output['user'] = true;
            $output['device'] = !is_null($device);
            $output['pin'] = !is_null($device?->pin);
            $output['specialist'] = !is_null($user->specialist) && $user->specialist->is_registered;
            $output['client'] = !is_null($user->client);
            $output['face'] = !is_null($device) ? $device?->face : false;
        } else {
            $output['user'] = false;
        }
        return $output;
    }

    /**
     * @param string $phoneNumber
     * @return bool
     * @throws GuzzleException
     * @throws InvalidLoginException
     * @throws SMSNotSentException
     */
    public function resendSms(string $phoneNumber): bool
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber, false) ?? throw new InvalidLoginException;

        if ($phoneNumber == config('custom.test_phone_number')) {
            $verification_code = '0000';
        } else {
            $verification_code = Random::generate(4, '0-9');
        }

        try {
            $this->SMSService->sendSms("$verification_code - код для входа в приложение Визитка.", $phoneNumber);
        } catch (SMSNotSentException $e) {
            throw new SMSNotSentException;
        }

        $user->verification_code = $verification_code;
        $user->save();

        return true;
    }

    /**
     * @param string $phoneNumber
     * @return bool
     * @throws GuzzleException
     * @throws InvalidLoginException
     * @throws SMSNotSentException
     */
    public function forget(string $phoneNumber): bool
    {
        $user = $this->service->searchByPhoneNumber($phoneNumber, false) ?? throw new InvalidLoginException;
        if ($phoneNumber == config('custom.test_phone_number')) {
            $verification_code = '0000';
        } else {
            $verification_code = Random::generate(4, '0-9');
        }

        try {
            $this->SMSService->sendSms(" $verification_code - код для сброса PIN Визитка.\nНикому не сообщайте код", $phoneNumber);
        } catch (SMSNotSentException $e) {
            throw new SMSNotSentException;
        }

        $user->verification_code = $verification_code;
        $user->save();

        return true;
    }

    /**
     * @param array $data
     * @return string|bool
     * @throws UserAlreadyVerifiedException
     * @throws UserNotFoundException
     * @throws VerificationCodeIsntValidException
     */
    public function verification(array $data): string|bool
    {
        $phoneNumber = $data['phone_number'];
        $verificationCode = $data['verification_code'];
        $userAlreadyExists = false;

        $user = $this->service->searchByPhoneNumber($phoneNumber, false);

        if (is_null($user)) {
            $user = $this->service->searchByPhoneNumber($phoneNumber) ?? throw new UserNotFoundException();
            $userAlreadyExists = true;
        }

        if ($user->is_verified && !$userAlreadyExists) {
            throw new UserAlreadyVerifiedException;
        }

        if ($user->verification_code == $verificationCode) {
            if (!$userAlreadyExists) {
                $user->phone_number_verified_at = Carbon::now();
                $user->is_verified = true;
                $user->save();
            }

            $this->deviceService->create([
                'device_id' => $data['device_id'],
                'user_id' => $user->id
            ]);

            return $user->createToken("Token for user #$user->id")->plainTextToken;
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
            if ($phoneNumber == config('custom.test_phone_number')) {
                $verification_code = '0000';
            } else {
                $verification_code = Random::generate(4, '0-9');
            }

            $this->SMSService->sendSms(
                "$verification_code - код для входа в приложение Визитка.", $user->phone_number
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
     * @param array $data
     * @return bool
     * @throws InvalidDeviceException
     * @throws InvalidLoginException
     */
    public function setPin(array $data): bool
    {
        $user = $this->service->searchByPhoneNumber($data['phone_number']) ?? throw new InvalidLoginException;
        $device = $this->deviceService->getDevice($data['device_id'], $user->id)
            ?? throw new InvalidDeviceException;

        return $this->deviceService->setPin($user->id, $device->device_id, $data['pin']);
    }

    /**
     * @param array $data
     * @return bool
     * @throws InvalidDeviceException
     * @throws InvalidLoginException
     */
    public function unsetPin(array $data): bool
    {
        $user = $this->service->searchByPhoneNumber($data['phone_number']) ?? throw new InvalidLoginException;
        $device = $this->deviceService->getDevice($data['device_id'], $user->id)
            ?? throw new InvalidDeviceException;

        return $this->deviceService->unsetPin($user->id, $device->device_id);
    }


    /**
     * @param Device $device
     * @param string $pin
     * @return bool
     */
    protected function attempt(Device $device, string $pin): bool
    {
        if ($device->pin == $pin) {
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @return bool
     * @throws InvalidDeviceException
     * @throws InvalidLoginException
     */
    public function setFace(array $data): bool
    {
        $user = $this->service->searchByPhoneNumber($data['phone_number']) ?? throw new InvalidLoginException;
        $device = $this->deviceService->getDevice($data['device_id'], $user->id)
            ?? throw new InvalidDeviceException;

        return $this->deviceService->setFace($user->id, $device->device_id);
    }

    /**
     * @param array $data
     * @return bool
     * @throws InvalidDeviceException
     * @throws InvalidLoginException
     */
    public function unsetFace(array $data): bool
    {
        $user = $this->service->searchByPhoneNumber($data['phone_number']) ?? throw new InvalidLoginException;
        $device = $this->deviceService->getDevice($data['device_id'], $user->id)
            ?? throw new InvalidDeviceException;

        return $this->deviceService->unsetFace($user->id, $device->device_id);
    }

    /**
     * @throws InvalidLoginException
     * @throws InvalidDeviceException
     */
    public function logout(array $data): bool
    {
        $device = $this->deviceService->getDevice($data['device_id'], $data['user_id'])
            ?? throw new InvalidDeviceException;

        $this->deviceService->removeDevice($data['user_id'], $device->device_id);
        auth()->user()->tokens()->delete();

        return true;
    }
}
