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

    public function addNew(array $data): bool
    {
        $settingsId = $this->maintenanceSettingsRepository->mySettings()->id;
        foreach ($data['maintenances'] as $item) {
            $item['specialist_id'] = $data['specialist_id'];
            $item['settings_id'] = $settingsId;
            $item['price'] = $item['price']['value'] ?? null;
            $item['duration'] = $item['duration']['value'];
            $this->repository->create($item);
        }
        return true;
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
        foreach ($data['maintenances'] as $maintenance) {
            $this->repository->getById($maintenance['id']) ?? throw new NotFoundHttpException;
            $maintenance['price'] = $maintenance['price']['value'];
            $maintenance['duration'] = $maintenance['duration']['value'];
            $this->repository->update($maintenance['id'], $maintenance);
        }

        return true;
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
