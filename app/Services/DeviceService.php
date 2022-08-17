<?php

namespace App\Services;

use App\Models\Device;
use App\Repositories\DeviceRepository;


class DeviceService
{
    public function __construct(
        protected DeviceRepository $repository
    ) {}

    public function create(array $data): bool
    {
        $this->repository->create($data);
        return true;
    }

    public function setPin(int $userId, string $device_id, string $pin): bool
    {
        $device = $this->repository->whereFirst([
            'user_id' => $userId,
            'device_id' => $device_id
        ]);
        $device->pin = $pin;
        $device->save();

        return true;
    }

    public function unsetPin(int $userId, string $device_id): bool
    {
        $device = $this->repository->whereFirst([
            'user_id' => $userId,
            'device_id' => $device_id
        ]);

        $device->pin = null;
        $device->save();

        return true;
    }

    public function getDevice(string $deviceId, int $userId): ?Device
    {
        return $this->repository->whereFirst([
            'device_id' => $deviceId,
            'user_id' => $userId
        ]);
    }

    public function setFace(int $userId, string $device_id,): bool
    {
        $device = $this->repository->whereFirst([
            'user_id' => $userId,
            'device_id' => $device_id
        ]);
        $device->face = true;
        $device->save();

        return true;
    }

    public function unsetFace(int $userId, string $device_id): bool
    {
        $device = $this->repository->whereFirst([
            'user_id' => $userId,
            'device_id' => $device_id
        ]);

        $device->face = false;
        $device->save();

        return true;
    }
}
