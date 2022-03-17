<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class ClientRepository extends Repository
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

    public function findByUserId($id)
    {
        return $this->model::where('user_id', $id)->first();
    }
}
