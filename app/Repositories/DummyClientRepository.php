<?php

namespace App\Repositories;

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
}
