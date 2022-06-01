<?php

namespace App\Services;

use App\Http\Resources\ContactBookResource;
use App\Http\Resources\DummyBusinessCardResource;
use App\Repositories\BusinessCardHolderRepository;
use App\Repositories\ContactBookRepository;
use App\Repositories\DummyBusinessCardRepository;
use App\Repositories\SpecialistRepository;


class DummyBusinessCardService
{
    public function __construct(
        protected DummyBusinessCardRepository $repository,
        protected SpecialistRepository $specialistRepository,
        protected ContactBookRepository $contactBookRepository
    ) {}

    public function create(array $data): DummyBusinessCardResource|ContactBookResource
    {
        $record = $this->specialistRepository->findByPhoneNumber($data['phone_number']);
        if (!is_null($record)) {
            return new ContactBookResource($this->contactBookRepository->create([
                'client_id' => auth()->user()->client->id,
                'specialist_id' => $record->id
            ]));
        }
        return new DummyBusinessCardResource($this->repository->create($data));
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
