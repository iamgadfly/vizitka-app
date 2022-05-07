<?php

namespace App\Repositories;

use App\Models\DummyClient;

class DummyClientRepository extends Repository
{
    public function __construct(DummyClient $model)
    {
        parent::__construct($model);
    }
}
