<?php

namespace App\Repositories;

use App\Models\Share;

class ShareRepository extends Repository
{
    public function __construct(Share $model)
    {
        parent::__construct($model);
    }
}
