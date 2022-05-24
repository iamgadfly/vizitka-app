<?php

namespace App\Services;

use App\Models\WorkScheduleDay;
use App\Repositories\SingleWorkScheduleRepository;
use App\Repositories\WorkScheduleDayRepository;
use Carbon\Carbon;


class SingleWorkScheduleService
{
    public function __construct(
        protected SingleWorkScheduleRepository $repository
    ) {}

    public function create(array $data)
    {
        $output = [];
        foreach ($data['dates'] as $date) {
            $weekday = str(Carbon::parse($date)->shortEnglishDayOfWeek)->lower();
            $data['date'] = $date;
            $data['day_id'] = WorkScheduleDayRepository::getDayFromString($weekday)->id;
            $output[] = $this->repository->create($data);
        }
        return $output;
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }
}
