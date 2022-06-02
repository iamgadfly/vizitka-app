<?php

namespace App\Services;

use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Helpers\WeekdayHelper;
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
            $breakType = null;
            $startFrom = null;
            if ($data['type']['value'] == 'flexible') {
                $breakType = $data['flexibleSchedule']['breakType']['value'];
            } elseif($data['type']['value'] == 'sliding') {
                $breakType = $data['flexibleSchedule']['breakType']['value'];
                $startFrom = $data['flexibleSchedule']['startFrom'];
            }
            $settings = $this->settingsRepository->create([
                'smart_schedule' => $data['smart_schedule'],
                'confirmation' => $data['confirmation'],
                'cancel_appointments' => $data['cancel_appointment']['value'],
                'limit_before' => $data['limit_before']['value'],
                'limit_after' => $data['limit_after']['value'],
                'specialist_id' => $data['specialist_id'],
                'type' => $data['type']['value'],
                'break_type' => $breakType,
                'start_from' => $startFrom
            ]);
            $settingsId = $settings->id;
            $type = $data['type']['value'];
            if ($type == 'standard') {
                $weekends = $data['standardSchedule']['weekends'];
                $workTime = $data['standardSchedule']['workTime'];
                $breaks   = $data['standardSchedule']['breaks'];
                $this->createStandardSchedule(
                    $settingsId, $weekends, $workTime, $breaks
                );
            } elseif ($type == 'flexible') {
                $data = $data['flexibleSchedule']['data'];
                $breaks = $data['flexibleSchedule']['breaks'];
                $this->createFlexbileScedule(
                    $settingsId, $data, $breakType, $breaks
                );
            } elseif ($type == 'sliding') {
                $workdaysCount = $data['slidingSchedule']['workdaysCount'];
                $weekdaysCount = $data['slidingSchedule']['weekdaysCount'];
                $data = $data['slidingSchedule']['data'];
                $breaks = $data['slidingSchedule']['breaks'];
                $this->createSlidingSchedule(
                    $settingsId, $workdaysCount, $weekdaysCount, $data, $breakType, $breaks
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

    private function createSlidingSchedule(
        int $settings_id, int $workdays_count, int $weekends_count, array $data, string $breakType, ?array $breaks
    ): void
    {
        $this->dayRepository->fillDaysForSlidingType($settings_id, $workdays_count, $weekends_count);
        foreach (range(0, $workdays_count) as $index) {
            $day = $data[$index];
            $dayNum = (int) $day['day'];
            $dayId = WorkScheduleDayRepository::getDayFromInt($dayNum)->id;

            $this->workRepository->create([
                'start' => $day['workTime']['start'],
                'end' => $day['workTime']['end'],
                'day_id' => $dayId
            ]);

            foreach ($day['breaks'] as $break) {
                $this->breakRepository->create([
                    'start' => $break['start'],
                    'end' => $break['end'],
                    'day_id' => $dayId
                ]);
            }
        }
        foreach (range(0, $weekends_count) as $index) {
            $day = $data[$workdays_count + $index];
            $dayNum = (int) $day['day'];
            $dayId = WorkScheduleDayRepository::getDayFromInt($dayNum)->id;
            $this->workRepository->create([
                'start' => null,
                'end' => null,
                'day_id' => $dayId
            ]);
        }
    }

    private function createStandardSchedule(int $settingsId, array $weekends, array $workTime, array $breaks): void
    {
        $this->dayRepository->fillDaysNotForSlidingType($settingsId);
        // Create workdays
        foreach (WeekdayHelper::getAll() as $weekday) {
            $dayId = WorkScheduleDayRepository::getDayFromString($weekday)->id;
            if (in_array($weekday, array_column($weekends, 'value'))) {
                $this->workRepository->create([
                    'start' => null,
                    'end' => null,
                    'day_id' => $dayId,
                ]);
            } else {
                $this->workRepository->create([
                    'start' => $workTime['start'],
                    'end' => $workTime['end'],
                    'day_id' => $dayId
                ]);
                // And create breaks
                foreach ($breaks as $break) {
                    $this->breakRepository->create([
                        'start' => $break['start'],
                        'end' => $break['end'],
                        'day_id' => $dayId
                    ]);
                }
            }
        }
    }

    private function createFlexbileScedule(int $settingsId, array $data, string $breakType, ?array $breaks): void
    {
        $this->dayRepository->fillDaysNotForSlidingType($settingsId);

        foreach ($data as $item) {
            $dayId = WorkScheduleDayRepository::getDayFromString($item['day']['value'])->id;
            $this->workRepository->create([
                'day_id' => $dayId,
                'start' => $item['workTime']['start'],
                'end' => $item['workTime']['end'],
            ]);

            // Create breaks
            if ($breakType == 'united') {
                foreach ($breaks as $break) {
                    $this->breakRepository->create([
                        'day_id' => $dayId,
                        'start' => $break['start'],
                        'end' => $break['end']
                    ]);
                }
            } else {
                foreach ($item['breaks'] as $break) {
                    $this->breakRepository->create([
                        'day_id' => $dayId,
                        'start' => $break['start'],
                        'end' => $break['end']
                    ]);
                }
            }
        }
    }
}
