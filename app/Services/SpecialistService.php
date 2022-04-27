<?php

namespace App\Services;

use App\Exceptions\MaintenanceSettingsIsAlreadyExistingException;
use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Models\Specialist;
use App\Models\User;
use App\Repositories\BusinessCardRepository;
use App\Repositories\MaintenanceRepository;
use App\Repositories\SpecialistRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkScheduleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SpecialistService
{
    public function __construct(
        protected SpecialistRepository $repository,
        protected BusinessCardRepository $businessCardRepository,
        protected UserRepository $userRepository,
        protected MaintenanceService $maintenanceService,
        protected WorkScheduleService $scheduleService
    ) {}

    /**
     * @throws MaintenanceSettingsIsAlreadyExistingException
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     */
    public function create(array $data): bool
    {
        try {
            DB::beginTransaction();

            // Create specialist and his business card
            $specialist = $this->repository->create($data);
            $data['specialist_id'] = $specialist->id;
            $this->businessCardRepository->create($data);

            // Create maintenances and work schedule for specialist
            $data['maintenance']['specialist_id'] = $specialist->id;
            $data['schedule']['specialist_id'] = $specialist->id;

            $this->maintenanceService->create($data['maintenance']);
            $this->scheduleService->create($data['schedule']);

            DB::commit();

            return true;
        } catch (\PDOException) {
            DB::rollBack();
            return false;
        }
    }

    public function update($data): bool
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

    public function findByUserId(int $id): ?Specialist
    {
        return $this->repository->findByUserId($id);
    }

    public function getSpecialistData($id): ?Specialist
    {
        if (is_null($id)) {
            $item = $this->repository->findByUserId(auth()->id());
        } else {
            $item = $this->repository->getById($id);
        }

        return $item;
    }

    public function getMe(): ?Specialist
    {
        return $this->getSpecialistData(null);
    }
}
