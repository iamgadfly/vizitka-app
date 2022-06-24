<?php

namespace App\Repositories;

use App\Exceptions\SpecialistNotFoundException;
use App\Models\Specialist;

class SpecialistRepository extends Repository
{
    public function __construct(Specialist $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        return $this->model::updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function findByUserId($id, bool $isRegistered = true): ?Specialist
    {
        return $this->model::where([
            'user_id' => $id,
            'is_registered' => $isRegistered
        ])->first() ?? throw new SpecialistNotFoundException;
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function findById(int $id, bool $isRegistered = true)
    {
        return $this->model::where([
            'id' => $id,
            'is_registered' => $isRegistered
        ])->first() ?? throw new SpecialistNotFoundException;
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function findByPhoneNumber(string $phoneNumber, bool $isRegistered = true)
    {
        return $this->model::whereHas('user', function ($q) use ($phoneNumber) {
            return $q->where('phone_number', $phoneNumber);
        })->where([
            'is_registered' => $isRegistered
        ])->get()->first() ?? throw new SpecialistNotFoundException;
    }
}
