<?php

namespace App\Repositories;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Models\ContactBook;

class ContactBookRepository extends Repository
{
    public function __construct(ContactBook $model)
    {
        parent::__construct($model);
    }

    public function thrashedRecord(int $clientId, string $type)
    {
        $type_id = $type == 'client' ? 'client_id' : 'dummy_client_id';
        return $this->model::onlyTrashed()
            ->where([
                'specialist_id' => auth()->user()->specialist->id,
                $type_id => $clientId
            ])->first();
    }

    public function create(array $data)
    {
        return $this->model::updateOrCreate($data, $data);
    }
}
