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
        protected WorkScheduleDayRepository $dayRepository,
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
                    ?? $this->dayRepository->getDayIndexFromDate($date)->id,
                'date' => $date,
                'start' => null,
                'end' => null,
                'is_break' => false
            ];

            $this->repository->create($weekend);
        }
        return true;
    }

    public function createBreak(array $data): bool
    {
        $weekday = str(Carbon::parse($data['date'])->shortEnglishDayOfWeek)->lower();
        $data['is_break'] = true;
        $data['day_id'] = WorkScheduleDayRepository::getDayFromString($weekday)?->id
            ?? $this->dayRepository->getDayIndexFromDate($data['date'])?->id;
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
