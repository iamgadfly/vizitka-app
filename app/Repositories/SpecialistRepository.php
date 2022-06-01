<?php

namespace App\Repositories;

use App\Models\Specialist;

class SpecialistRepository extends Repository
{
    public function __construct(Specialist $model)
    {
        parent::__construct($model);
    }

    public function findByUserId($id): ?Specialist
    {
        return $this->model::where('user_id', $id)->first();
    }

    public function findByPhoneNumber(string $phoneNumber)
    {
        return $this->model::whereHas('user', function ($q) use ($phoneNumber) {
            return $q->where('phone_number', $phoneNumber);
        })->get()->first();
    }
}
