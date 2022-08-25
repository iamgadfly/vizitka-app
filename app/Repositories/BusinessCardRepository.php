<?php

namespace App\Repositories;

use App\Models\BusinessCard;

class BusinessCardRepository extends Repository
{
    public function __construct(BusinessCard $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        $model = $this->whereFirst([
            'specialist_id' => auth()->user()->specialist->id
        ]);
        if (!is_null($model)) {
            $model->delete();
        }
        return $this->model::create($data);
    }
}
