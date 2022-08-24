<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\UserNotFoundException;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Repositories\ContactDataRepository;
use App\Repositories\UserRepository;

class ClientService
{
    public function __construct(
        protected ClientRepository $repository,
        protected UserRepository $userRepository,
        protected ContactDataRepository $contactDataRepository
    ) {}

    /**
     * @param array $data
     * @return Client
     */
    public function create(array $data): Client
    {
        return $this->repository->create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        return $this->repository->update($data['id'], $data);
    }

    /**
     * @param $id
     * @return Client|null
     */
    public function findByUserId($id): ?Client
    {
        return $this->repository->findByUserId($id);
    }

    /**
     * @param $id
     * @return Client|null
     */
    public function getClientData($id): ?Client
    {
        if (is_null($id)) {
            return $this->repository->findByUserId(auth()->id());
        }
        return $this->repository->getById($id);
    }

    /**
     * @return Client
     * @throws UserNotFoundException
     */
    public function getMe(): Client
    {
        return $this->getClientData(null) ?? throw new UserNotFoundException;
    }
}
