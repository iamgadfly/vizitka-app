<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Models\Specialist;
use App\Repositories\ContactBookRepository;


class ContactBookForClientService
{
    public function __construct(
        protected ContactBookRepository $repository
    ) {}

    /**
     * @throws RecordIsAlreadyExistsException
     */
    public function create(int $specialistId)
    {
        $clientId = auth()->user()->client->id;
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
            $specialist = Specialist::whereHas('user', function ($q) use ($phoneNumber) {
                return $q->where(['phone_number' => $phoneNumber]);
            })->get();

            if (!is_null($specialist->first())) {
                try {
                    $output[] = $this->create($specialist->first()->id);
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
    public function delete(int $specialistId)
    {
        $record = $this->repository->whereFirst([
            'specialist_id' => $specialistId
        ]);
        if (is_null($record)) {
            throw new RecordNotFoundException;
        }
        return $record->delete();
    }

    public function get(int $clientId)
    {
        return $this->repository->whereGet([
            'client_id' => $clientId
        ]);
    }
}
