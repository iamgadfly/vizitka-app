<?php

namespace App\Services;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Models\ContactBook;
use App\Models\Specialist;
use App\Repositories\ContactBookRepository;
use App\Repositories\DummyBusinessCardRepository;
use App\Repositories\SpecialistRepository;
use Illuminate\Support\Collection;


class ContactBookForClientService
{
    public function __construct(
        protected ContactBookRepository $repository,
        protected DummyBusinessCardRepository $businessCardRepository,
        protected SpecialistRepository $specialistRepository
    ) {}

    /**
     * @param int $specialistId
     * @param int|null $clientId
     * @return ContactBook
     *
     * @throws ClientNotFoundException
     * @throws RecordIsAlreadyExistsException
     */
    public function create(int $specialistId, int $clientId = null): ContactBook
    {
        if (is_null($clientId)) {
            $clientId = AuthHelper::getClientIdFromAuth();
        }
        $record = $this->repository->whereFirst([
            'specialist_id' => $specialistId,
            'client_id' => $clientId
        ]);
        if (!is_null($record)) {
            throw new RecordIsAlreadyExistsException;
        }
        return $this->repository->create([
            'client_id' => $clientId,
            'specialist_id' => $specialistId
        ]);
    }

    /**
     * @param array $data
     * @return array
     * @throws ClientNotFoundException
     */
    public function massCreate(array $data): array
    {
        $output = [];
        foreach ($data['data'] as $item) {
            try {
                $specialist = $this->specialistRepository->findByPhoneNumber($item['phone_number']);
            } catch (SpecialistNotFoundException $e) {
                $specialist = null;
            }

            if (!is_null($specialist)) {
                try {
                    $output[] = $this->create($specialist->id);
                } catch (RecordIsAlreadyExistsException $e) {
                    continue;
                }
            } else {
                $output[] = $this->businessCardRepository->create([
                    'name' => $item['name'],
                    'surname' => $item['surname'],
                    'phone_number' => $item['phone_number'],
                    'title' => null,
                    'avatar_id' => null,
                    'about' => null,
                    'client_id' => AuthHelper::getClientIdFromAuth()
                ]);
            }
        }
        return $output;
    }


    /**
     * @param int $specialistId
     * @param string $type
     * @return bool|null
     * @throws ClientNotFoundException
     * @throws RecordNotFoundException
     */
    public function delete(int $specialistId, string $type): ?bool
    {
        if ($type == 'specialist') {
            $record = $this->repository->whereFirst([
                'specialist_id' => $specialistId,
                'client_id' => AuthHelper::getClientIdFromAuth()
            ]);
        } else {
            $record = $this->businessCardRepository->whereFirst([
                'id' => $specialistId
            ]);
        }
        if (is_null($record)) {
            throw new RecordNotFoundException;
        }
        return $record->delete();
    }

    /**
     * @param int $clientId
     * @return Collection
     */
    public function get(int $clientId): Collection
    {
        $contacts = $this->repository->whereGet([
            'client_id' => $clientId
        ]);

        $dummies = $this->businessCardRepository->whereGet([
            'client_id' => $clientId
        ]);

        return $contacts->concat($dummies);
    }
}
