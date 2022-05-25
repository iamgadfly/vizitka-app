<?php

namespace App\Repositories;

use App\Models\Blacklist;

class BlacklistRepository extends Repository
{
    public function __construct(Blacklist $model)
    {
        parent::__construct($model);
    }
}
