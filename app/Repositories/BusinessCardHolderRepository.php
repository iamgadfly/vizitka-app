<?php

namespace App\Repositories;

use App\Models\BusinessCardHolder;

class BusinessCardHolderRepository extends Repository
{
    public function __construct(BusinessCardHolder $model)
    {
        parent::__construct($model);
    }
}
