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
            $schedule = $data['schedules'];
            if ($data['type'] !== 'sliding') {
                $this->createNotSlidingSchedule($settings->id, $schedule['work'], $schedule['breaks']);
            } else {
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

    public function mySettings()
    {
        return new WorkScheduleSettingsResource($this->settingsRepository->mySettings());
    }

    private function createNotSlidingSchedule(int $settings_id, array $workdays, array $breaks)
    {
        $days = $this->dayRepository->fillDaysNotForSlidingType($settings_id);
        foreach (array_map(null, $days, $workdays) as $pair) {
            $pair[1]['day_id'] = $pair[0]->id;
            $this->workRepository->create($pair[1]);
        }
    }

    private function createSlidingSchedule(
        int $settings_id, array $workdays, array $breaks, int $workdays_count, int $weekends_count
    )
    {
        $days = $this->dayRepository->fillDaysForSlidingType($settings_id, $workdays_count, $weekends_count);
        // Create work days
        foreach (array_map(null, $days, $workdays) as $pair) {
            $pair[1]['day_id'] = $pair[0]->id;
            $this->workRepository->create($pair[1]);
        }
    }
}
