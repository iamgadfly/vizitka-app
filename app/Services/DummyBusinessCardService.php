<?php

namespace App\Services;

use App\Repositories\DummyBusinessCardRepository;


class DummyBusinessCardService
{
    public function __construct(
        protected DummyBusinessCardRepository $repository
    ) {}

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

    public function get(int $id)
    {
        return $this->repository->getById($id);
    }
}
