<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Models\Client;
use App\Repositories\ContactBookRepository;


class ContactBookService
{
    public function __construct(
        protected ContactBookRepository $repository,
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

    public function get(int $specialistId)
    {
        return $this->repository->whereGet([
            'specialist_id' => $specialistId
        ]);
    }
}
