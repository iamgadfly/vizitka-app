<?php

namespace App\Services;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
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

    /**
     * @throws ClientNotFoundException
     */
    public function create(array $data): DummyBusinessCardResource|BusinessCardResource
    {
        try {
            $record = $this->specialistRepository->findByPhoneNumber($data['phone_number']);
        }  catch (SpecialistNotFoundException) {
            $record = null;
        }
        if (!is_null($record)) {
            $recordItem = $this->contactBookRepository->whereFirst([
                'client_id' => AuthHelper::getClientIdFromAuth(),
                'specialist_id' => $record->id
            ]);
            if (is_null($recordItem)) {
                $recordItem = $this->contactBookRepository->create([
                    'client_id' => AuthHelper::getClientIdFromAuth(),
                    'specialist_id' => $record->id
                ]);
            }
            return new BusinessCardResource($recordItem->specialist->card);
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
