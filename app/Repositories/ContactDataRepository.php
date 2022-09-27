<?php

namespace App\Repositories;

use App\Models\ContactData;

/**
 * @method ContactData whereFirst(array $condition)
 */
class ContactDataRepository extends Repository
{
    public function __construct(ContactData $model)
    {
        parent::__construct($model);
    }
}
