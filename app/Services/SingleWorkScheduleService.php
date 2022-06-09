<?php

namespace App\Services;

use App\Helpers\TimeHelper;
use App\Repositories\SingleWorkScheduleRepository;
use App\Repositories\WorkScheduleDayRepository;
use App\Repositories\WorkScheduleSettingsRepository;
use Carbon\Carbon;


class SingleWorkScheduleService
{
    public function __construct(
        protected SingleWorkScheduleRepository $repository,
    ) {}

    public function create(array $data): bool
    {
        if ($data['is_break']) {
            return $this->createBreak($data['break']);
        }
        $output = [];
        $dates = TimeHelper::getDateInterval($data['weekend']['start'], $data['weekend']['end']);
        foreach ($dates as $date) {
            $weekday = str(Carbon::parse($date)->shortEnglishDayOfWeek)->lower();
            $weekend = [
                'day_id' => WorkScheduleDayRepository::getDayFromString($weekday)->id
                    ?? WorkScheduleDayRepository::getDayIndexFromDate($date)->id,
                'date' => $date,
                'start' => null,
                'end' => null,
                'is_break' => false
            ];

            $this->repository->create($weekend);
        }
        return true;
    }

    public function createWorkday(array $data): bool
    {
        foreach ($data['dates'] as $date) {
            $weekday = str(Carbon::parse($date)->shortEnglishDayOfWeek)->lower();
            $dayId = WorkScheduleDayRepository::getDayFromString($weekday)->id
                ?? WorkScheduleDayRepository::getDayIndexFromDate($date)->id;
            $recordWorkday = [
                'day_id' => $dayId,
                'date' => $date,
                'start' => $data['workTime']['start'],
                'end' => $data['workTime']['end'],
                'is_break' => false
            ];
            $this->repository->create($recordWorkday);
            foreach ($data['breaks'] as $break) {
                $recordBreak = [
                    'day_id' => $dayId,
                    'date' => $date,
                    'start' => $break['start'],
                    'end' => $break['end'],
                    'is_break' => true
                ];
                $this->repository->create($recordBreak);
            }
        }
        return true;
    }

    public function createBreak(array $data): bool
    {
        $weekday = str(Carbon::parse($data['date'])->shortEnglishDayOfWeek)->lower();
        $data['is_break'] = true;
        $data['day_id'] = WorkScheduleDayRepository::getDayFromString($weekday)?->id
            ?? WorkScheduleDayRepository::getDayIndexFromDate($data['date'])?->id;
        $data['start'] = $data['time']['start'];
        $data['end'] = $data['time']['end'];
        $this->repository->create($data);
        return true;
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }
}
