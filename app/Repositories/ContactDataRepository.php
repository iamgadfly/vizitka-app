<?php

namespace App\Repositories;

use App\Models\ContactData;

class ContactDataRepository extends Repository
{
    public function __construct(ContactData $model)
    {
        parent::__construct($model);
    }
}
