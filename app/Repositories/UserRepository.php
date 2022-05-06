<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        return $this->model::updateOrCreate([
            'phone_number' => $data['phone_number']
        ], $data);
    }

    public function searchByPhoneNumber(string $number, bool $verified = true): ?User
    {
        return $this->model::where([
            'phone_number' => $number,
            'is_verified' => $verified
        ])->first();
    }
}
