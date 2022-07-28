<?php

namespace App\Services;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Repositories\PillDisableRepository;
use Carbon\Carbon;


class PillDisableService
{
    public function __construct(
        protected PillDisableRepository $repository
    ) {}

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function getAllByDate(string $date, ?int $specialistId)
    {
        if (is_null($specialistId)) {
            $specialistId = AuthHelper::getSpecialistIdFromAuth();
        }
        return $this->repository->whereGet([
            'date' => $date,
            'specialist_id' => $specialistId
        ]);
    }
}
