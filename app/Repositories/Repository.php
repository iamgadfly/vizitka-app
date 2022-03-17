<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository
{
    public function __construct(
        protected Model $model
    ) {}

    public function all()
    {
        return $this->model::all();
    }

    public function getById($id)
    {
        return $this->model::find($id);
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model::find($id);

        return $model->update($data);
    }

    public function deleteById($id)
    {
        $model = $this->model::find($id);

        return $model->delete();
    }
}
