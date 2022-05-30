<?php

namespace App\Repositories;

use App\Models\ContactBook;

class ContactBookRepository extends Repository
{
    public function __construct(ContactBook $model)
    {
        parent::__construct($model);
    }

    public function thrashedRecord(int $clientId)
    {
        return $this->model::onlyTrashed()
            ->where([
                'specialist_id' => auth()->user()->specialist->id,
                'client_id' => $clientId
            ])->first();
    }
}
