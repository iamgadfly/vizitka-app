<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Models\Client;
use App\Repositories\ContactBookRepository;
use App\Repositories\DummyClientRepository;


class ContactBookService
{
    public function __construct(
        protected ContactBookRepository $repository,
        protected DummyClientRepository $dummyClientRepository
    ) {}

    /**
     * @throws RecordIsAlreadyExistsException
     */
    public function create(int $clientId)
    {
        $specialistId = auth()->user()->specialist->id;
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
     * @throws RecordIsAlreadyExistsException
     */
    public function massCreate(array $data): array
    {
        $output = [];
        foreach ($data['phone_numbers'] as $phoneNumber) {
            $client = Client::whereHas('user', function ($q) use ($phoneNumber) {
                return $q->where(['phone_number' => $phoneNumber]);
            })->get();

            if (!is_null($client->first())) {
                try {
                    $output[] = $this->create($client->first()->id);
                } catch (RecordIsAlreadyExistsException $e) {
                    continue;
                }
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
        ]);
        if (is_null($record)) {
            throw new RecordNotFoundException;
        }
        return $record->delete();
    }

    public function get(int $specialistId)
    {
        $clients = $this->repository->whereGet([
            'specialist_id' => $specialistId
        ]);
        if (!empty($clients)) {
            $clients->map(function ($client) {
                $client->type = 'client';
            });
        }

        $dummies = $this->dummyClientRepository->whereGet([
            'specialist_id' => $specialistId
        ]);
        if (!empty($dummies)) {
            $dummies->map(function ($client) {
                $client->type = 'dummy';
            });
        }
        return $clients->concat($dummies);
    }
}
