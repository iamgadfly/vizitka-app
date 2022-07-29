<?php

namespace App\Services;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Repositories\PillDisableRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class PillDisableService
{
    public function __construct(
        protected PillDisableRepository $repository
    ) {}

    public function create(array $data): bool
    {
        $this->repository->create($data);
        return true;
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function delete(string $time, string $date)
    {
        $pill = $this->repository->whereFirst([
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
            'date' => $date,
            'time' => $time
        ]);
        return $pill->delete();
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function getAllByDate(string $date, ?int $specialistId): ?Collection
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
