<?php

namespace App\Services;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Models\Specialist;
use App\Repositories\ContactBookRepository;
use App\Repositories\DummyBusinessCardRepository;
use App\Repositories\SpecialistRepository;


class ContactBookForClientService
{
    public function __construct(
        protected ContactBookRepository $repository,
        protected DummyBusinessCardRepository $businessCardRepository,
        protected SpecialistRepository $specialistRepository
    ) {}

    /**
     * @throws RecordIsAlreadyExistsException
     * @throws ClientNotFoundException
     */
    public function create(int $specialistId, int $clientId = null)
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
                try {
                    $output[] = $this->businessCardRepository->create([
                        'name' => $item['name'],
                        'surname' => $item['surname'],
                        'phone_number' => $item['phone_number'],
                        'title' => null,
                        'avatar_id' => null,
                        'about' => null,
                        'client_id' => AuthHelper::getClientIdFromAuth()
                    ]);
                } catch (RecordIsAlreadyExistsException $e) {
                    continue;
                }
            }
        }
        return $output;
    }


    /**
     * @throws RecordNotFoundException
     * @throws ClientNotFoundException
     */
    public function delete(int $specialistId, string $type)
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

    public function get(int $clientId)
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
