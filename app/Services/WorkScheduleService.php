<?php

namespace App\Services;

use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Http\Resources\WorkScheduleSettingsResource;
use App\Repositories\WorkScheduleBreakRepository;
use App\Repositories\WorkScheduleDayRepository;
use App\Repositories\WorkScheduleSettingsRepository;
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
    public function create(array $data): WorkScheduleSettingsResource
    {
        $settings = $this->settingsRepository->mySettings();
        if ($settings) {
            throw new WorkScheduleSettingsIsAlreadyExistingException;
        }
        try {
            \DB::beginTransaction();
            $settings = $this->settingsRepository->create($data);
            $schedule = $data['schedules'];
            if ($data['type'] !== 'sliding') {
                $this->createNotSlidingSchedule($settings->id, $schedule['work'], $schedule['breaks']);
            } elseif($data['type']) {
                $this->createSlidingSchedule(
                    $settings->id, $schedule['work'], $schedule['breaks'],
                    $data['workdays_count'], $data['weekends_count']
                );
            }
            \DB::commit();
            return new WorkScheduleSettingsResource($settings);
        } catch (\PDOException $e) {
            \DB::rollBack();
            throw new \PDOException($e);
        }
    }

    public function mySettings(): WorkScheduleSettingsResource
    {
        return new WorkScheduleSettingsResource($this->settingsRepository->mySettings());
    }

    private function createNotSlidingSchedule(int $settings_id, array $workdays, array $breaks): void
    {
        $this->dayRepository->fillDaysNotForSlidingType($settings_id);
        foreach ($workdays as $workday) {
            $workday['day_id'] = WorkScheduleDayRepository::getDayFromString($workday['day'])->id;
            $this->workRepository->create($workday);
        }
        foreach ($breaks as $break) {
            $break['day_id'] = WorkScheduleDayRepository::getDayFromString($break['day'])->id;
            $this->breakRepository->create($break);
        }
    }

    private function createSlidingSchedule(
        int $settings_id, array $workdays, array $breaks, int $workdays_count, int $weekends_count
    ): void
    {
        $this->dayRepository->fillDaysForSlidingType($settings_id, $workdays_count, $weekends_count);
        // Create work days
        foreach ($workdays as $workday) {
            $workday['day_id'] = WorkScheduleDayRepository::getDayFromInt($workday['day'])->id;
            $this->workRepository->create($workday);
        }
        foreach ($breaks as $break) {
            $break['day_id'] = WorkScheduleDayRepository::getDayFromInt($break['day'])->id;
            $this->breakRepository->create($break);
        }
    }
}
