<?php

namespace App\Services;

use App\Exceptions\UserPinException;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Nette\Utils\Random;

class UserService
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function create(array $data)
    {
        $data['verification_code'] = Random::generate(4, '0-9');
        return $this->repository->create($data);
    }

    public function searchByPhoneNumber(string $number)
    {
        return $this->repository->searchByPhoneNumber($number);
    }

    public function searchByPhoneNumberNotNull(string $number)
    {
        return $this->repository->searchByPhoneNumberNotNull($number);
    }

    /**
     * @throws UserPinException
     */
    public function attemptPIN(Authenticatable $user, string $pin): bool
    {
        if ($user->pin == $pin) {
            return true;
        }

        throw new UserPinException();
    }
}
