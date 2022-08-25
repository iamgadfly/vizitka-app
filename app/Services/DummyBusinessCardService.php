<?php

namespace App\Services;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Http\Resources\BusinessCardResource;
use App\Http\Resources\DummyBusinessCardResource;
use App\Models\DummyBusinessCard;
use App\Repositories\ContactBookRepository;
use App\Repositories\DummyBusinessCardRepository;
use App\Repositories\SpecialistRepository;
use Illuminate\Database\Eloquent\Collection;


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

    /**
     * @param array $data
     * @return BusinessCardResource|bool
     * @throws ClientNotFoundException
     */
    public function update(array $data)
    {
        try {
            $record = $this->specialistRepository->findByPhoneNumber($data['phone_number']);
        }  catch (\Exception) {
            $record = null;
        }

        if (!is_null($record)) {
            $recordItem = $this->contactBookRepository->whereFirst([
                'client_id' => AuthHelper::getClientIdFromAuth(),
                'specialist_id' => $record->id
            ]);
            $this->repository->deleteById($data['id']);
            if (is_null($recordItem)) {
                $recordItem = $this->contactBookRepository->create([
                    'client_id' => AuthHelper::getClientIdFromAuth(),
                    'specialist_id' => $record->id
                ]);
            }
            return new BusinessCardResource($recordItem->specialist->card);
        }

        return $this->repository->update($data['id'], $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function get(int $id): DummyBusinessCard
    {
        return $this->repository->getById($id);
    }

    /**
     * @param string $phoneNumber
     * @return \Illuminate\Support\Collection
     */
    public function getByPhoneNumber(string $phoneNumber): \Illuminate\Support\Collection
    {
        return $this->repository->whereGet([
            'phone_number' => $phoneNumber
        ]);
    }
}
