<?php

namespace App\Services;

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

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $maintenanceSettings = $this->maintenanceSettingsRepository->create([
                $data['finance_analytics'], $data['many_maintenances'],
            ]);
            $maintenances = [];
            foreach ($data['maintenances'] as $maintenance) {
                $maintenance['settings_id'] = $maintenanceSettings->id;
                $maintenance['specialist_id'] = $data['specialist_id'];
                $item = $this->repository->create($maintenance);
                $maintenances[] = $item;
            }
            DB::commit();
            return $maintenances;
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \PDOException($e);
        }
    }

    public function getMySettings()
    {
        return new MaintenanceSettingsResource(
            $this->maintenanceSettingsRepository->mySettings()->first()
        );
    }
}
