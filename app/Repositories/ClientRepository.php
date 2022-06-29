<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository extends Repository
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

    public function findByUserId($id): ?Client
    {
        return $this->model::where('user_id', $id)->first();
    }

    public function findByPhoneNumber(string $phoneNumber): ?Client
    {
        return $this->model::whereHas('user', function ($q) use ($phoneNumber) {
            return $q->where(['phone_number' => $phoneNumber]);
        })->first();
    }
}
