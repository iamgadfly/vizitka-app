<?php

namespace App\Services;

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

    public function findByUserId($id)
    {
        return $this->repository->findByUserId($id);
    }

    public function getClientData($id)
    {
        if (is_null($id)) {
            $client = $this->repository->findByUserId(auth()->id())->toArray();
        } else {
            $client = $this->repository->getById($id)->toArray();
        }
        $client['phone'] = $this->userRepository->getById($client['user_id'])->phone_number;

        return $client;
    }

    public function getMe()
    {
        return $this->getClientData(null);
    }
}
