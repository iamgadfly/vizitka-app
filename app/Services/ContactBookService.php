<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Repositories\ContactBookRepository;


class ContactBookService
{
    public function __construct(
        protected ContactBookRepository $repository,
    ) {}

    public function massCreate(array $data)
    {
        $output = [];
        foreach ($data['phone_numbers'] as $phoneNumber) {
            $client = Client::whereHas('user', function ($q) use ($phoneNumber) {
                return $q->where(['phone_number' => $phoneNumber]);
            })->get();
            if (!is_null($client->first())) {
                $output[] = $this->repository->create([
                    'client_id' => $client->first()->id,
                    'specialist_id' => auth()->user()->specialist->id
                ]);
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
