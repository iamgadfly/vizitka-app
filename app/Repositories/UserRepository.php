<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function searchByPhoneNumber(string $number)
    {
        return $this->model::where('phone_number', $number)->first();
    }
}
