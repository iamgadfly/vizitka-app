<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Nette\Utils\Random;

class UserService
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function create(array $data)
    {
        $data['verification_code'] = Random::generate(4, '0-9');
        $data['is_verified'] = false;
        return $this->repository->create($data);
    }

    public function searchByPhoneNumber(string $number, bool $verified = true): ?User
    {
        return $this->repository->searchByPhoneNumber($number, $verified);
    }
}
