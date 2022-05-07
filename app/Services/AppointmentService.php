<?php

namespace App\Services;

use App\Exceptions\TimeIsNotValidException;
use App\Repositories\AppointmentRepository;
use Carbon\Carbon;


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
        return $this->repository->getAllByDate($date);
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
