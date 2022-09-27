<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Models\Client;
use App\Models\ContactBook;
use App\Repositories\ClientRepository;
use App\Repositories\ContactBookRepository;
use App\Repositories\ContactDataRepository;
use App\Repositories\DummyBusinessCardRepository;
use App\Repositories\DummyClientRepository;
use Illuminate\Support\Collection;


class ContactBookService
{
    public function __construct(
        protected ContactBookRepository $repository,
        protected DummyClientRepository $dummyClientRepository,
        protected ClientRepository $clientRepository,
        protected DummyBusinessCardRepository $dummyBusinessCardRepository,
        protected ContactDataRepository $contactDataRepository
    ) {}

    /**
     * @param array $data
     * @return ContactBook
     * @throws RecordIsAlreadyExistsException
     * @throws SpecialistNotFoundException
     */
    public function create(array $data): ContactBook
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
     * @param array $data
     * @return array
     * @throws SpecialistNotFoundException
     */
    public function massCreate(array $data): array
    {
        $output = [];
        foreach ($data['data'] as $item) {
            if (!isset($item['phone_number'])) {
                continue;
            }
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
                try {
                    $client = $this->dummyClientRepository->create([
                        'name' => $item['name'],
                        'surname' => $item['surname'],
                        'phone_number' => $item['phone_number'],
                        'discount' => 0,
                        'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
                        'content_url' => $item['avatar']
                    ]);
                    $output[] = $this->create([
                        'type' => 'dummy',
                        'client_id' => $client->id
                    ]);
                } catch (RecordIsAlreadyExistsException $e) {
                    continue;
                }
            }
        }
        return $output;
    }

    /**
     * @param array $data
     * @return bool|null
     * @throws RecordNotFoundException
     */
    public function delete(array $data): ?bool
    {
        $clientId = $data['client_id'];
        if ($data['type'] == 'client') {
            $record = $this->repository->whereFirst([
                'client_id' => $clientId
            ]);

            if ($record) {
                $this->repository->setInvisible($record);
                return true;
            }
        } else {
            $record = $this->dummyClientRepository->whereFirst([
                'id' => $clientId
            ]);
        }
        if (is_null($record)) {
            throw new RecordNotFoundException;
        }
        return $record->forceDelete();
    }

    /**
     * @param int $specialistId
     * @return Collection
     */
    public function get(int $specialistId): Collection
    {
        $items = $this->repository->whereGet([
            'specialist_id' => $specialistId,
            'is_visible' => true
        ]);
        $items->map(function ($item) {
            if (is_null($item->client)) {
                return;
            }
            $item->contactData = $this->contactDataRepository->whereFirst([
                'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
                'client_id' => $item->client->id
            ]);
        });
        return $items;
    }

    /**
     * @param int $clientId
     * @return Collection
     */
    public function getForClient(int $clientId): Collection
    {
        $contacts = $this->repository->whereGet([
            'client_id' => $clientId
        ]);

        $dummies = $this->dummyBusinessCardRepository->whereGet([
            'client_id' => $clientId
        ]);

        return $contacts->concat($dummies);
    }

    /**
     * @param array $data
     * @return bool
     * @throws SpecialistNotFoundException
     */
    public function massDelete(array $data): bool
    {
        foreach ($data['client_ids'] as $clientId) {
            $item = $this->repository->whereFirst([
                'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
                'client_id' => $clientId
            ]);
            $item->delete();
        }
        foreach ($data['dummy_client_ids'] as $clientId) {
            $item = $this->dummyClientRepository->getById($clientId);
            $item->delete();
        }
        return true;
    }
}
