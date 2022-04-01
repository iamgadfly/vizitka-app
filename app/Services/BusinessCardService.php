<?php

namespace App\Services;

use App\Repositories\BusinessCardRepository;


class BusinessCardService
{
    public function __construct(
        protected BusinessCardRepository $repository,
    ) {}

    public function get(int $id)
    {
        return $this->repository->getById($id);
    }
}
