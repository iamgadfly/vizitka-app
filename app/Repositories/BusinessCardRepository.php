<?php

namespace App\Repositories;

use App\Models\BusinessCard;

class BusinessCardRepository extends Repository
{
    public function __construct(BusinessCard $model)
    {
        parent::__construct($model);
    }
}
