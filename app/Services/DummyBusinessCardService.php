<?php

namespace App\Services;

use App\Http\Resources\BusinessCardResource;
use App\Http\Resources\DummyBusinessCardResource;
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

    public function create(array $data)
    {
        $record = $this->specialistRepository->findByPhoneNumber($data['phone_number']);
        if (!is_null($record)) {
            $record = $this->contactBookRepository->whereFirst([
                'client_id' => auth()->user()->client->id,
                'specialist_id' => $record->id
            ]);
            if (!is_null($record)) {
                return new BusinessCardResource($record->specialist->card);
            }
            $record = $this->contactBookRepository->create([
                'client_id' => auth()->user()->client->id,
                'specialist_id' => $record->id
            ]);
            return new BusinessCardResource($record->specialist->card);
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
