<?php

namespace App\Services;

use App\Events\SpecialistCreatedEvent;
use App\Exceptions\RecordNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Exceptions\WorkScheduleSettingsIsAlreadyExistingException;
use App\Helpers\AuthHelper;
use App\Helpers\WeekdayHelper;
use App\Http\Resources\WorkScheduleSettingsResource;
use App\Repositories\SpecialistRepository;
use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use App\Repositories\WorkSchedule\WorkScheduleDayRepository;
use App\Repositories\WorkSchedule\WorkScheduleSettingsRepository;
use App\Repositories\WorkSchedule\WorkScheduleWorkRepository;

class WorkScheduleService
{
    public function __construct(
        protected WorkScheduleSettingsRepository $settingsRepository,
        protected WorkScheduleBreakRepository $breakRepository,
        protected WorkScheduleDayRepository $dayRepository,
        protected WorkScheduleWorkRepository $workRepository,
        protected SpecialistRepository $specialistRepository
    ) {}

    /**
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     */
    public function create(array $data, bool $onUpdate = false): WorkScheduleSettingsResource
    {
        $settings = $this->settingsRepository->mySettings();
        if ($settings) {
            throw new WorkScheduleSettingsIsAlreadyExistingException;
        }
        try {
            \DB::beginTransaction();
            $breakType = null;
            $startFrom = null;
            $workdaysCount = null;
            $weekdaysCount = null;
            if ($data['type']['value'] == 'flexible') {
                $breakType = $data['flexibleSchedule']['breakType']['value'];
            } elseif($data['type']['value'] == 'sliding') {
                $breakType = $data['slidingSchedule']['breakType']['value'];
                $startFrom = $data['slidingSchedule']['startFrom']['value'];
                $workdaysCount = $data['slidingSchedule']['workdaysCount'];
                $weekdaysCount = $data['slidingSchedule']['weekdaysCount'];
            }
            $settings = $this->settingsRepository->create([
                'smart_schedule' => $data['smart_schedule'],
                'confirmation' => $data['confirmation'],
                'cancel_appointment' => $data['cancel_appointment']['value'],
                'limit_before' => $data['limit_before']['value'],
                'limit_after' => $data['limit_after']['value'],
                'specialist_id' => $data['specialist_id'],
                'type' => $data['type']['value'],
                'break_type' => $breakType,
                'start_from' => $startFrom,
                'workdays_count' => $workdaysCount,
                'weekdays_count' => $weekdaysCount
            ]);

            if ($data['type']['value'] == 'sliding') {
                $this->dayRepository->fillDaysForSlidingType(
                    $settings->id, $settings->workdays_count, $settings->weekdays_count
                );
            } else {
                $this->dayRepository->fillDaysNotForSlidingType($settings->id);
            }

            if (!$onUpdate) {
                $specialist = $settings->specialist;
                $specialist->is_registered = true;
                $specialist->save();
                event(new SpecialistCreatedEvent($specialist));
            }

            $type = $data['type']['value'];
            if ($type == 'standard') {
                $weekends = $data['standardSchedule']['weekends'];
                $workTime = $data['standardSchedule']['workTime'];
                $breaks   = $data['standardSchedule']['breaks'];
                $this->createStandardSchedule(
                    $weekends, $workTime, $breaks
                );
            } elseif ($type == 'flexible') {
                $workTime = $data['flexibleSchedule']['data'];
                $breaks = $data['flexibleSchedule']['breaks'];
                $this->createFlexibleSchedule(
                    $workTime, $breakType, $breaks
                );
            } elseif ($type == 'sliding') {
                $workdaysCount = $data['slidingSchedule']['workdaysCount'];
                $weekdaysCount = $data['slidingSchedule']['weekdaysCount'];
                $work = $data['slidingSchedule']['data'];
                $breaks = $data['slidingSchedule']['breaks'];
                $this->createSlidingSchedule(
                    $workdaysCount, $weekdaysCount, $work, $breakType, $breaks
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

    /**
     * @throws SpecialistNotFoundException
     * @throws WorkScheduleSettingsIsAlreadyExistingException
     * @throws RecordNotFoundException
     */
    public function update(array $data): WorkScheduleSettingsResource
    {
        $settings = $this->settingsRepository->whereFirst(['specialist_id' => AuthHelper::getSpecialistIdFromAuth()])
            ?? throw new RecordNotFoundException;
        $settings?->delete();

        return $this->create($data, true);
    }

    private function createSlidingSchedule(
        int $workdays_count, int $weekends_count, array $data, string $breakType, ?array $breaks
    ): void
    {
        foreach (range(0, $workdays_count - 1) as $index) {
            $day = $data[$index];
            $dayNum = (int) $day['day'];
            $dayId = WorkScheduleDayRepository::getDayFromInt($dayNum)->id;

            $this->workRepository->create([
                'start' => $day['workTime']['start'],
                'end' => $day['workTime']['end'],
                'day_id' => $dayId
            ]);

            if ($breakType == 'individual') {
                foreach ($day['breaks'] as $break) {
                    $this->breakRepository->create([
                        'start' => $break['start'],
                        'end' => $break['end'],
                        'day_id' => $dayId
                    ]);
                }
            } else {
                foreach ($breaks as $break) {
                    $this->breakRepository->create([
                        'start' => $break['start'],
                        'end' => $break['end'],
                        'day_id' => $dayId
                    ]);
                }
            }
        }
        foreach (range(1, $weekends_count) as $index) {
            $day = $workdays_count + $index;
            $dayId = WorkScheduleDayRepository::getDayFromInt($day)->id;
            $this->workRepository->create([
                'start' => null,
                'end' => null,
                'day_id' => $dayId
            ]);
        }
    }

    private function createStandardSchedule(array $weekends, array $workTime, array $breaks): void
    {
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

    private function createFlexibleSchedule(array $data, string $breakType, ?array $breaks): void
    {
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
