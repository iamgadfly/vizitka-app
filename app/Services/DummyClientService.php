<?php

namespace App\Services;

use App\Repositories\DummyClientRepository;


class DummyClientService
{
    public function __construct(
        protected DummyClientRepository $repository
    ) {}

    public function get(int $id)
    {
        return $this->repository->getById($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(array $data)
    {
        return $this->repository->update($data['id'], $data);
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }
}
