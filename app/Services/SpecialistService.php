<?php

namespace App\Services;

use App\Events\SpecialistCreatedEvent;
use App\Exceptions\MaintenanceSettingsIsAlreadyExistingException;
use App\Exceptions\SpecialistNotCreatedException;
use App\Exceptions\SpecialistNotFoundException;
use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Helpers\AuthHelper;
use App\Helpers\CardBackgroundHelper;
use App\Models\Specialist;
use App\Repositories\BusinessCardRepository;
use App\Repositories\SpecialistRepository;
use App\Repositories\UserRepository;

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
     * @throws SpecialistNotCreatedException
     */
    public function create(array $data)
    {
        try {
            \DB::beginTransaction();
            if (!is_null($data['avatar']['id'])) {
                $data['avatar_id'] = $data['avatar']['id'];
            }
            if (!isset($data['title'])) {
                $data['title'] = $data['activity_kind']['label'];
            }
            $data['activity_kind_id'] = $data['activity_kind']['value'];
            $data['background_image'] = CardBackgroundHelper::filenameFromActivityKind(
                $data['background_image']['value']
            );
            // Create specialist and his business card
            $specialist = $this->repository->create($data);
            $data['specialist_id'] = $specialist->id;
            $this->businessCardRepository->create($data);

            \DB::commit();

            return $specialist;
        } catch (\PDOException $e) {
            \DB::rollBack();
            throw new SpecialistNotCreatedException;
        }
    }

    public function update($data): bool
    {
        try {
            \DB::beginTransaction();
            if (!is_null($data['avatar']['id'])) {
                $data['avatar_id'] = $data['avatar']['id'];
            }
            if (!isset($data['title'])) {
                $data['title'] = $data['activity_kind']['label'];
            }
            $data['activity_kind_id'] = $data['activity_kind']['value'];
            $this->repository->update($data['id'], $data);
            $data['card_id'] = $this->repository->getById($data['id'])->card->id;
            $data['background_image'] = CardBackgroundHelper::filenameFromActivityKind($data['background_image']['value']);
            $this->businessCardRepository->update($data['card_id'], $data);
            \DB::commit();

            return true;
        } catch (\PDOException) {
            \DB::rollBack();
            return false;
        }
    }

    public function findByUserId(int $id): ?Specialist
    {
        return $this->repository->findByUserId($id);
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function getSpecialistData($id): ?Specialist
    {
        if (is_null($id)) {
            $item = $this->repository->findByUserId(auth()->id());
        } else {
            $item = $this->repository->getById($id);
        }

        return $item;
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function getMe(): ?Specialist
    {
        return $this->getSpecialistData(null);
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function getMyCard()
    {
        $specialist = $this->repository->getById(AuthHelper::getSpecialistIdFromAuth());
        $card = str($specialist->card->background_image)
            ->replace('images/card_backgrounds/', '')
            ->replace('.jpg', '')
            ->value();
        return CardBackgroundHelper::getCardFromActivityKind($card);
    }
}
