<?php

namespace App\Repositories;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Models\BusinessCard;

class BusinessCardRepository extends Repository
{
    public function __construct(BusinessCard $model)
    {
        parent::__construct($model);
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function create(array $data)
    {
        $this->whereFirst([
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth()
        ])->delete();
        return $this->model::create($data);
    }
}
