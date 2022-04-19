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

    public function searchByPhoneNumberNotNull(string $number): User
    {
        return $this->model::where('phone_number', $number)
            ->whereNotNull('phone_number_verified_at')->firstOrFail();
    }

        public function searchByPhoneNumber(string $number, bool $verified = true): User
    {
        return $this->model::where([
            'phone_number' => $number,
            'is_verified' => $verified
        ])->firstOrFail();
    }
}
