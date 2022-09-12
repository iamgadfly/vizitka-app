<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Http\Requests\ContactData\UpdateRequest;
use App\Models\ContactData;
use App\Repositories\ContactDataRepository;


class ContactDataService
{
    public function __construct(
        protected ContactDataRepository $repository
    ) {}

    /**
     * @throws SpecialistNotFoundException
     */
    public function update(array $data): ContactData
    {
        $item = $this->repository->whereFirst([
            'client_id' => $data['client_id'],
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth()
        ]);
        if (is_null($item)) {
            return $this->repository->create($data);
        }
        $this->repository->update($item->id, $data);
        return $item->refresh();
    }
}
