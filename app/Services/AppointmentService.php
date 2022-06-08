<?php

namespace App\Services;

use App\Exceptions\TimeIsNotValidException;
use App\Helpers\SvgHelper;
use App\Helpers\TimeHelper;
use App\Repositories\AppointmentRepository;
use App\Repositories\MaintenanceRepository;
use App\Repositories\WorkScheduleBreakRepository;
use App\Repositories\WorkScheduleWorkRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nette\Utils\Random;


class AppointmentService
{
    public function __construct(
        protected AppointmentRepository $repository,
        protected MaintenanceRepository $maintenanceRepository
    ) {}

    /**
     * @throws TimeIsNotValidException
     */
    public function create(array $data, ?string $orderNumber = null): array
    {
        $orderNumber = $orderNumber ?? Random::generate(5, '0-9');
        $start = Carbon::parse($data['time_start']);
        $output = [];
        foreach ($data['maintenance_ids'] as $maintenanceId) {
            $maintenance = $this->maintenanceRepository->whereFirst([
                'id' => $maintenanceId
            ]);
            $appointment = [
                'dummy_client_id' => $data['dummy_client_id'] ?? null,
                'client_id' => $data['client_id'] ?? null,
                'specialist_id' => $data['specialist_id'],
                'date' => $data['date'],
                'maintenance_id' => $maintenanceId,
                'time_start' => $start->format('H:i'),
                'time_end' => $start->addMinutes($maintenance->duration)->format('H:i'),
                'order_number' => $orderNumber
            ];
            $this->isInInterval($appointment);
            $output[] = $this->repository->create($appointment);
        }
        return $output;
    }

    /**
     * @throws TimeIsNotValidException
     */
    public function update(array $data): array
    {
        $appointments = $this->repository->whereGet([
            'order_number' => $data['order_number']
        ]);
        foreach ($appointments as $appointment) {
            $this->repository->deleteById($appointment->id);
        }
        return $this->create($data, $data['order_number']);
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }

    public function get(string $orderNumber)
    {
        return $this->repository->whereGet(['order_number' => $orderNumber]);
    }

    public function confirm(string $orderNumber): bool
    {
        return $this->repository->confirm($orderNumber);
    }

    public function skipped(string $orderNumber): bool
    {
        return $this->repository->skipped($orderNumber);
    }

    public function getAllByDay(string $date): Collection
    {
        $output = collect();
        $appointments = $this->repository->getAllByDate($date);
        // TODO: Uncomment this in future
//        $breaks = WorkScheduleBreakRepository::getBreaksForDay($date);
//        $output->breaks = $breaks;
        $output->appointments = $appointments;
        $times = WorkScheduleWorkRepository::getWorkDay($date) ?? null;
        if (!is_null($times)) {
            $timesobj = new \StdClass;
            $timesobj->start = Carbon::parse($date . $times[0])->toISOString();
            $timesobj->end = Carbon::parse($date . $times[1])->toISOString();
        } else {
            $timesobj = null;
        }
        $output->workSchedule = $timesobj;
        return $output;
    }

    public function getSvgForPeriod(array $dates): array
    {
        $output = [];
        foreach ($dates as $date) {
            $days = TimeHelper::getMonthInterval($date);
            foreach ($days as $day) {
                $schedule = WorkScheduleWorkRepository::getWorkDay($day);
                if (is_null($schedule)) {
                    continue;
                }
                $output[$day] = $this->getSvgForDate($day, $schedule[0], $schedule[1]);
            }
        }

        return $output;
    }

    public function getSvgForDate(string $date, string $minTime, string $maxTime): array
    {
        $appointments = $this->repository->getAllByDate($date);
        $breaks = WorkScheduleBreakRepository::getBreaksForDay($date);
        return $this->convertToScheduleType($appointments, $breaks, $minTime, $maxTime);
    }

    public function getMinMaxTimes(string $date): array
    {
        $starts = [];
        $ends = [];
        $weekDates = TimeHelper::getWeekdays($date);
        foreach ($weekDates as $weekDate) {
            $times = WorkScheduleWorkRepository::getWorkDay($weekDate) ?? null;
            if (!is_null($times)) {
                $starts[] = $times[0];
                $ends[] = $times[1];
            }
        }

        return [
            min($starts),
            max($ends)
        ];
    }

    public function massDelete(array $data): bool
    {
        foreach ($data['ids'] as $id) {
            $this->repository->deleteById($id);
        }
        return true;
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

    private function convertToScheduleType($appointments, $breaks, string $minTime, string $maxTime): array
    {
        /*
            Worship Allah, and be of those who give thanks.
            (Quran 39:66)
            Blessed be the name of thy Lord, full of Majesty, Bounty, and Honor. (Quran 55:78)
            So celebrate with praises the name of thy Lord, the Supreme.
            (Quran 59:56)
            Praise be to Allah, who has guided us to this.
            Never could we have found guidance, had it not been for the guidance of Allah.
            (Quran 7:43)
            And He is Allah, there is no god but He. To Him be praise, at the first and at the last.
            For Him is the command, and to Him shall you be brought back. (Quran 28:70)
            Then Praise be to Allah, Lord of the heavens and Lord of the earth. Lord and Cherisher of all the worlds!
            To Him be Glory throughout the heavens and the earth, And He is Exalted in Power, Full of Wisdom!
            (Quran 45:36-37)
         */
        $intervalsCount = TimeHelper::getTimeIntervalAsInt($minTime, $maxTime) / 15;
        $sectionOffset = 70 / $intervalsCount;
        $sectionOffsetValue = $sectionOffset;
        $interval = TimeHelper::getTimeIntervalAsFreeAppointment($minTime, $maxTime);
        $convertedAppointments = [];
        $convertedBreaks = [];
        foreach ($appointments as $appointment)
        {
            $start = Carbon::parse($appointment->time_start)->format('H:i');
            $end = Carbon::parse($appointment->time_end)->format('H:i');
            foreach ($interval as $index => $item) {
                // try to find interval intersections
                if ($item['start'] >= $start && $item['end'] <= $end) {
                    unset($interval[$index]);
                }
            }
            $convertedAppointments[] = [
                'start' => $start,
                'end' => $end,
                'status' => $appointment->status
            ];
        }

        foreach ($breaks as $break)
        {
            $start = Carbon::parse($break[0])->format('H:i');
            $end = Carbon::parse($break[1])->format('H:i');
            foreach ($interval as $index => $item) {
                if ($item['start'] >= $start && $item['end'] <= $end) {
                    unset($interval[$index]);
                }
            }
            $convertedBreaks[] = [
                'start' => Carbon::parse($break[0])->format('H:i'),
                'end' => Carbon::parse($break[1])->format('H:i'),
                'status' => 'break'
            ];
        }
        $svg = [];
        $all = array_merge($convertedAppointments, $convertedBreaks, $interval);

        usort($all, function ($a, $b) {
            return $a['start'] > $b['start'] ? 1 : -1;
        });

        foreach ($all as $item) {
            if ($item['status'] == 'free') {
                $svg[] = [
                    'stroke' => SvgHelper::getColorFromType($item['status']),
                    'strokeDasharray' => $sectionOffsetValue,
                    'strokeDashoffset' => -$sectionOffset
                ];
                $sectionOffset += $sectionOffsetValue;
            } else {
                $sectionsCount = TimeHelper::getTimeIntervalAsInt($item['start'], $item['end']) / 15;
                foreach (range(1, $sectionsCount) as $value) {
                    $svg[] = [
                        'stroke' => SvgHelper::getColorFromType($item['status']),
                        'strokeDasharray' => $sectionOffsetValue,
                        'strokeDashoffset' => -$sectionOffset
                    ];
                    $sectionOffset += $sectionOffsetValue;
                }
            }
        }
        return $svg;
    }
}
