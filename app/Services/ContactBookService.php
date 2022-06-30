<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Repositories\ContactBookRepository;
use App\Repositories\DummyBusinessCardRepository;
use App\Repositories\DummyClientRepository;


class ContactBookService
{
    public function __construct(
        protected ContactBookRepository $repository,
        protected DummyClientRepository $dummyClientRepository,
        protected ClientRepository $clientRepository,
        protected DummyBusinessCardRepository $dummyBusinessCardRepository
    ) {}

    /**
     * @throws RecordIsAlreadyExistsException
     * @throws SpecialistNotFoundException
     */
    public function create(array $data)
    {
        $clientId = $data['client_id'];
        if ($data['type'] == 'client') {
            $type = 'client_id';
        } else {
            $type = 'dummy_client_id';
        }
        $specialistId = AuthHelper::getSpecialistIdFromAuth();
        $record = $this->repository->whereFirst([
            'specialist_id' => $specialistId,
            $type => $clientId
        ]);
        if (!is_null($record)) {
            throw new RecordIsAlreadyExistsException;
        }
        return $this->repository->create([
            $type => $clientId,
            'specialist_id' => $specialistId
        ]);
    }

    /**
     * @throws RecordIsAlreadyExistsException
     * @throws SpecialistNotFoundException
     */
    public function massCreate(array $data): array
    {
        $output = [];
        foreach ($data['data'] as $item) {
            $client = $this->clientRepository->findByPhoneNumber($item['phone_number']);

            if (!is_null($client)) {
                try {
                    $output[] = $this->create([
                        'type' => 'client',
                        'client_id' => $client->id
                    ]);
                } catch (RecordIsAlreadyExistsException $e) {
                    continue;
                }
            } else {
                $client = $this->dummyClientRepository->create([
                    'name' => $item['name'],
                    'surname' => $item['surname'],
                    'phone_number' => $item['phone_number'],
                    'discount' => 0,
                    'specialist_id' => AuthHelper::getSpecialistIdFromAuth()
                ]);
                $output[] = $this->create([
                    'type' => 'dummy',
                    'client_id' => $client->id
                ]);
            }
        }
        return $output;
    }

    /**
     * @throws RecordNotFoundException
     */
    public function delete(int $clientId)
    {
        $record = $this->repository->whereFirst([
            'client_id' => $clientId
        ]) ?? $this->dummyClientRepository->getById($clientId);
        if (is_null($record)) {
            throw new RecordNotFoundException;
        }
        return $record->delete();
    }

    public function get(int $specialistId)
    {
        return $this->repository->whereGet([
            'specialist_id' => $specialistId
        ]);
    }

    public function getForClient(int $clientId)
    {
        $contacts = $this->repository->whereGet([
            'client_id' => $clientId
        ]);

        $dummies = $this->dummyBusinessCardRepository->whereGet([
            'client_id' => $clientId
        ]);

        return $contacts->concat($dummies);
    }
}
