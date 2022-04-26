<?php

namespace App\Services;

use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Http\Resources\WorkScheduleSettingsResource;
use App\Repositories\WorkScheduleSettingsRepository;
use App\Repositories\WorkScheduleRepository;

class WorkScheduleService
{
    public function __construct(
        protected WorkScheduleSettingsRepository $settingsRepository,
        protected WorkScheduleRepository $repository
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
            $data['specialist_id'] = auth()->user()->specialist->id;
            $data['weekends'] = json_encode($data['weekends']);
            $settings = $this->settingsRepository->create($data);
            foreach ($data['schedules'] as $schedule) {
                $schedule['settings_id'] = $settings->id;
                $this->repository->create($schedule);
            }
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
