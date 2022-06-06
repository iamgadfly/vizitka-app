<?php

namespace App\Services;

use App\Exceptions\MaintenanceSettingsIsAlreadyExistingException;
use App\Http\Resources\MaintenanceSettingsResource;
use App\Repositories\MaintenanceRepository;
use App\Repositories\MaintenanceSettingsRepository;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MaintenanceService
{
    public function __construct(
        protected MaintenanceRepository $repository,
        protected MaintenanceSettingsRepository $maintenanceSettingsRepository
    ) {}


    public function create(array $data): MaintenanceSettingsResource
    {
        $settings = $this->maintenanceSettingsRepository->mySettings();
        if ($settings) {
            $this->repository->deleteAllForCurrentUser($settings->id);
//            throw new MaintenanceSettingsIsAlreadyExistingException;
        }

        try {
            DB::beginTransaction();
            $maintenanceSettings = $this->maintenanceSettingsRepository->create($data);
            foreach ($data['maintenances'] as $maintenance) {
                $maintenance['price'] = $maintenance['price']['value'];
                $maintenance['duration'] = $maintenance['duration']['value'];
                $maintenance['settings_id'] = $maintenanceSettings->id;
                $maintenance['specialist_id'] = $data['specialist_id'];
                $this->repository->create($maintenance);
            }
            DB::commit();
            return new MaintenanceSettingsResource($maintenanceSettings);
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \PDOException($e);
        }
    }

    public function addNew(array $data)
    {
        $data['settings_id'] = $this->maintenanceSettingsRepository->mySettings()->id;
        $data['price'] = $data['price']['value'];
        $data['duration'] = $data['duration']['value'];
        return $this->repository->create($data);
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }

    public function updateSettings(array $data)
    {
        $item = $this->maintenanceSettingsRepository->mySettings() ?? throw new NotFoundHttpException;
        return $this->maintenanceSettingsRepository->update($item->id, $data);
    }

    public function update(array $data)
    {
        $this->repository->getById($data['id']) ?? throw new NotFoundHttpException;
        return $this->repository->update($data['id'], $data);
    }

    public function getMySettings(): MaintenanceSettingsResource
    {
        return new MaintenanceSettingsResource(
            $this->maintenanceSettingsRepository->mySettings()
        );
    }

    public function all(int $specialistId)
    {
        return $this->repository->allForCurrentUser($specialistId);
    }
}
