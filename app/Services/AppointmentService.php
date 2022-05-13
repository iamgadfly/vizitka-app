<?php

namespace App\Services;

use App\Exceptions\TimeIsNotValidException;
use App\Helpers\ImageHelper;
use App\Helpers\TimeHelper;
use App\Repositories\AppointmentRepository;
use App\Repositories\WorkScheduleBreakRepository;
use App\Repositories\WorkScheduleWorkRepository;
use Carbon\Carbon;
use Ramsey\Uuid\Type\Time;


class AppointmentService
{
    public function __construct(
        protected AppointmentRepository $repository
    ) {}

    /**
     * @throws TimeIsNotValidException
     */
    public function create(array $data)
    {
        $this->isInInterval($data);
        return $this->repository->create($data);
    }

    /**
     * @throws TimeIsNotValidException
     */
    public function update(array $data)
    {
        $this->isInInterval($data);
        return $this->repository->update($data['id'], $data);
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }

    public function get(int $id)
    {
        return $this->repository->getById($id);
    }

    public function confirm(int $id)
    {
        return $this->repository->confirm($id);
    }

    public function skipped(int $id)
    {
        return $this->repository->skipped($id);
    }

    public function getAllByDay(string $date)
    {
        try {
            $output = collect();
            $times = WorkScheduleWorkRepository::getWorkDay($date);
            $timesobj = new \StdClass;
            $timesobj->start = Carbon::parse($date . $times[0])->toISOString();
            $timesobj->end = Carbon::parse($date . $times[1])->toISOString();
            $output->workSchedule = $timesobj;
            $appointments = $this->repository->getAllByDate($date);
            foreach ($appointments as $appointment) {
                $appointment->time_start = Carbon::parse($appointment->date . $appointment->time_start);
                $appointment->time_end = Carbon::parse($appointment->date . $appointment->time_end);
            }
            $output->appointments = $appointments;
            return $output;
        } catch (\Error $e) {
            return [];
        }
    }

    /**
     * @throws TimeIsNotValidException
     */
    private function isInInterval(array $data): void
    {
        $appointments = $this->repository->getAllByDate($data['date']);
        $start = strtotime(Carbon::parse($data['time_start'])->format('H:i'));
        $end = strtotime(Carbon::parse($data['time_end'])->format('H:i'));
        foreach ($appointments as $appointment) {
            $appointment_start = strtotime(Carbon::parse($appointment->time_start)->format('H:i'));
            $appointment_end = strtotime(Carbon::parse($appointment->time_end)->format('H:i'));
            if (($start >= $appointment_start && $start < $appointment_end)
                || ($end > $appointment_start && $end <= $appointment_end)
                || ($start < $appointment_start && $end > $appointment_end)
            )
            {
                throw new TimeIsNotValidException;
            }
        }
    }
}
