<?php

namespace App\Repositories;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Models\DummyClient;

class DummyClientRepository extends Repository
{
    public function __construct(DummyClient $model)
    {
        parent::__construct($model);
    }

    public function allForCurrentSpecialist(int $specialistId)
    {
        return $this->model::where('specialist_id', $specialistId)->get();
    }

    public function create(array $data)
    {
        $item = $this->whereFirst([
            'specialist_id' => $data['specialist_id'],
            'phone_number' => $data['phone_number']
        ]);
        if (!is_null($item)) {
            throw new RecordIsAlreadyExistsException;
        }
        return $this->model::updateOrCreate($data, $data);
    }

}
