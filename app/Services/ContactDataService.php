<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Http\Requests\ContactData\UpdateRequest;
use App\Models\ContactData;
use App\Repositories\ContactDataRepository;
use App\Repositories\DummyClientRepository;


class ContactDataService
{
    public function __construct(
        protected ContactDataRepository $repository,
        protected DummyClientRepository $dummyClientRepository
    ) {}

    /**
     * @throws SpecialistNotFoundException
     */
    public function update(array $data): ContactData
    {
        $data['discount'] = $data['discount']['value'];
        if ($data['type'] == 'client') {
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
        $item = $this->dummyClientRepository->getById($data['client_id']) ?? throw new RecordNotFoundException();
        $this->dummyClientRepository->update($item->id, $data);
        return $item->refresh();
    }
}
