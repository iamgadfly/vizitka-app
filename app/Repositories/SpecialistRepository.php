<?php

namespace App\Repositories;

use App\Models\Specialist;
use Illuminate\Database\Eloquent\Model;

class SpecialistRepository extends Repository
{
    public function __construct(Specialist $model)
    {
        parent::__construct($model);
    }

    public function findByUserId($id)
    {
        return $this->model::where('user_id', $id)->first();
    }
}
