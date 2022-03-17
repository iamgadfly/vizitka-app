<?php

namespace App\Services;

use App\Enums\ActivityKind;
use App\Repositories\SpecialistRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class SpecialistService
{
    public function __construct(
        protected SpecialistRepository $repository,
        protected UserRepository $userRepository
    ) {}

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function findByUserId(int $id)
    {
        return $this->repository->findByUserId($id);
    }

    public function getSpecialistData($id)
    {
        $item = $this->repository->getById($id)->toArray();
        $item['phone'] = $this->userRepository->getById($item['user_id'])->phone_number;
        $item['activity_kind'] = ActivityKind::fromInt($item['activity_kind_id']);

        return $item;
    }
}
