<?php

namespace App\Services;

use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\RecordNotFoundException;
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

    /**
     * @throws RecordIsAlreadyExistsException
     */
    public function create(array $data): bool
    {
        $pill = $this->repository->whereFirst([
            'specialist_id' => $data['specialist_id'],
            'date' => $data['date'],
            'time' => $data['time']
        ]);
        if (!is_null($pill)) {
            throw new RecordIsAlreadyExistsException;
        }
        $this->repository->create($data);
        return true;
    }

    /**
     * @throws SpecialistNotFoundException
     * @throws RecordNotFoundException
     */
    public function delete(string $time, string $date)
    {
        $pill = $this->repository->whereFirst([
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
            'date' => $date,
            'time' => $time
        ]) ?? throw new RecordNotFoundException();
        return $pill->delete();
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function getAllByDate(string $date, ?int $specialistId = null): ?Collection
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
