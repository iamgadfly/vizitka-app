<?php

namespace App\Repositories;

use App\Models\ContactBook;

class ContactBookRepository extends Repository
{
    public function __construct(ContactBook $model)
    {
        parent::__construct($model);
    }
}
