<?php

namespace App\Services;

use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Http\Resources\WorkScheduleSettingsResource;
use App\Repositories\WorkScheduleBreakRepository;
use App\Repositories\WorkScheduleDayRepository;
use App\Repositories\WorkScheduleSettingsRepository;
use App\Repositories\WorkScheduleRepository;
use App\Repositories\WorkScheduleWorkRepository;

class WorkScheduleService
{
    public function __construct(
        protected WorkScheduleSettingsRepository $settingsRepository,
        protected WorkScheduleBreakRepository $breakRepository,
        protected WorkScheduleDayRepository $dayRepository,
        protected WorkScheduleWorkRepository $workRepository,
    ) {}

    /**
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     */
    public function create(array $data)
    {
        $settings = $this->settingsRepository->mySettings();
        if ($settings) {
            throw new WorkScheduleSettingsIsAlreadyExistingException;
        }
        try {
            \DB::beginTransaction();
            $settings = $this->settingsRepository->create($data);
            \DB::commit();
            return new WorkScheduleSettingsResource($settings);
        } catch (\PDOException $e) {
            \DB::rollBack();
            throw new \PDOException($e);
        }
    }

    public function mySettings()
    {
        return new WorkScheduleSettingsResource($this->settingsRepository->mySettings());
    }
}
