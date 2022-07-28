<?php

namespace App\Repositories;

use App\Models\PillDisable;

class PillDisableRepository extends Repository
{
    public function __construct(PillDisable $model)
    {
        parent::__construct($model);
    }
}
