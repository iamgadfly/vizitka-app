<?php

namespace App\Repositories;

use App\Models\DummyBusinessCard;

class DummyBusinessCardRepository extends Repository
{
    public function __construct(DummyBusinessCard $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        return $this->model::updateOrCreate([
            'phone_number' => $data['phone_number']
        ], $data);
    }
}
