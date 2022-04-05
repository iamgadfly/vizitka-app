<?php

namespace App\Services;

use App\Enums\ActivityKind;
use App\Models\User;
use App\Repositories\BusinessCardRepository;
use App\Repositories\SpecialistRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SpecialistService
{
    public function __construct(
        protected SpecialistRepository $repository,
        protected BusinessCardRepository $businessCardRepository,
        protected UserRepository $userRepository
    ) {}

    public function create(array $data): bool
    {
        try {
            DB::beginTransaction();

            $specialist = $this->repository->create($data);
            $data['specialist_id'] = $specialist->id;
            $this->businessCardRepository->create($data);

            DB::commit();

            return true;
        } catch (\PDOException) {
            DB::rollBack();
            return false;
        }
    }

    public function update($data)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($data['id'], $data);
            $data['card_id'] = $this->repository->getById($data['id'])->card->id;
            $this->businessCardRepository->update($data['card_id'], $data);
            DB::commit();

            return true;
        } catch (\PDOException) {
            DB::rollBack();
            return false;
        }
    }

    public function findByUserId(int $id)
    {
        return $this->repository->findByUserId($id);
    }

    public function getSpecialistData($id)
    {
        if (is_null($id)) {
            $item = $this->repository->findByUserId(auth()->id());
        } else {
            $item = $this->repository->getById($id);
        }

        return $item;
    }

    public function getMe()
    {
        return $this->getSpecialistData(null);
    }
}
