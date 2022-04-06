<?php

namespace App\Repositories;

use App\Models\DummyBusinessCard;

class DummyBusinessCardRepository extends Repository
{
    public function __construct(DummyBusinessCard $model)
    {
        parent::__construct($model);
    }
}
