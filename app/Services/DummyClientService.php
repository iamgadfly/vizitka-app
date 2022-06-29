<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\SpecialistNotFoundException;
use App\Repositories\DummyClientRepository;


class DummyClientService
{
    public function __construct(
        protected DummyClientRepository $repository,
        protected ContactBookService $service
    ) {}

    public function get(int $id)
    {
        return $this->repository->getById($id);
    }

    /**
     * @throws SpecialistNotFoundException
     * @throws RecordIsAlreadyExistsException
     */
    public function create(array $data)
    {
        if ($data['discount']['value'] != 0) {
            $data['discount'] = $data['discount']['value'] / 100;
        } else {
            $data['discount'] = 0;
        }
        $client = $this->repository->create($data);
        $this->service->create([
            'client_id' => $client->id,
            'type' => 'dummy'
        ]);

        return $client;
    }

    public function update(array $data)
    {
        return $this->repository->update($data['id'], $data);
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }

    public function all(int $specialistId)
    {
        return $this->repository->allForCurrentSpecialist($specialistId);
    }
}
