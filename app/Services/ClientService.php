<?php

namespace App\Services;

use App\Exceptions\UserNotFoundException;
use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;

class ClientService
{
    public function __construct(
        protected ClientRepository $repository,
        protected UserRepository $userRepository
    ) {}

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(array $data)
    {
        return $this->repository->update($data['id'], $data);
    }

    public function findByUserId($id)
    {
        return $this->repository->findByUserId($id);
    }

    public function getClientData($id)
    {
        if (is_null($id)) {
            return $this->repository->findByUserId(auth()->id());
        }
        return $this->repository->getById($id);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getMe()
    {
        return $this->getClientData(null) ?? throw new UserNotFoundException;
    }
}
