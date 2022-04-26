<?php

namespace App\Services;

use App\Exceptions\MaintenanceSettingsIsAlreadyExistingException;
use App\Http\Resources\MaintenanceSettingsResource;
use App\Repositories\MaintenanceRepository;
use App\Repositories\MaintenanceSettingsRepository;
use Illuminate\Support\Facades\DB;


class MaintenanceService
{
    public function __construct(
        protected MaintenanceRepository $repository,
        protected MaintenanceSettingsRepository $maintenanceSettingsRepository
    ) {}

    /**
     * @throws MaintenanceSettingsIsAlreadyExistingException
     */
    public function create(array $data)
    {
        $settings = $this->maintenanceSettingsRepository->mySettings();
        if ($settings) {
            throw new MaintenanceSettingsIsAlreadyExistingException;
        }

        try {
            DB::beginTransaction();
            $maintenanceSettings = $this->maintenanceSettingsRepository->create($data);
            foreach ($data['maintenances'] as $maintenance) {
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

    public function getMySettings()
    {
        return new MaintenanceSettingsResource(
            $this->maintenanceSettingsRepository->mySettings()
        );
    }
}
