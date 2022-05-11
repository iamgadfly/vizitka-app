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
        $output = [];
        $result = [];
        $i = 1;
        foreach (TimeHelper::getWeekdays($date) as $date) {
            $breaks = WorkScheduleBreakRepository::getBreaksForDay($date);
            foreach ($breaks as $break) {
                if (is_null($break[0]) && is_null($break[1])) {
                    continue;
                }
                $item = [
                    'time' => TimeHelper::getTimeInterval($break[0], $break[1]),
                    'status' => 'break'
                ];
                $output[TimeHelper::formatDateForResponse($date)][] = $item;
            }
            $times = WorkScheduleWorkRepository::getWorkDay($date);
            if (empty($times)){
                continue;
            }
            $interval = TimeHelper::getTimeInterval($times[0], $times[1]);
            $all = $this->repository->getAllByDate(Carbon::parse($date)->format('Y-m-d'))->sortBy('time_start');
            if ($all->isNotEmpty()) {
                foreach ($all as $appointment) {
                    $item = [
                        'time' => TimeHelper::getTimeInterval($appointment->time_start, $appointment->time_end),
                        'name' => $appointment->client->name ?? $appointment->dummyClient->name,
                        'surname' => $appointment->client->surname ?? $appointment->dummyClient->surname,
                        'service' => $appointment->maintenance->title,
                        'photo' => ImageHelper::getAssetFromFilename($appointment->client->avatar->url
                            ?? $appointment->dummyClient->avatar->url),
                        'status' => $appointment->status
                    ];
                    $output[TimeHelper::formatDateForResponse($date)][] = $item;
                }
            }
            $busy = [];
            foreach (array_column($output[TimeHelper::formatDateForResponse($date)], 'time') as $value) {
                $busy = array_merge($busy, $value);
            }

            foreach ($busy as $time) {
                $key = array_search($time, $interval);
                unset($interval[$key]);
                $interval = array_values($interval);
            }
            foreach ($interval as $index => $time) {
                $interval[$index] = ['time' => [$time]];
            }
            $arr = array_merge($interval, $output[TimeHelper::formatDateForResponse($date)] ?? []);
            usort($arr, function ($a, $b) {
                return Carbon::parse($a['time'][0]) > Carbon::parse($b['time'][0]);
            });
            $i++;

            $result[TimeHelper::formatDateForResponse($date)] = $arr;
        }
        return response()->json($result);
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
