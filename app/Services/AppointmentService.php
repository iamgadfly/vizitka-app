<?php

namespace App\Services;

use App\Exceptions\SpecialistNotFoundException;
use App\Exceptions\TimeIsNotValidException;
use App\Helpers\AuthHelper;
use App\Helpers\ImageHelper;
use App\Helpers\SvgHelper;
use App\Helpers\TimeHelper;
use App\Models\WorkScheduleSettings;
use App\Repositories\AppointmentRepository;
use App\Repositories\MaintenanceRepository;
use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use App\Repositories\WorkSchedule\WorkScheduleSettingsRepository;
use App\Repositories\WorkSchedule\WorkScheduleWorkRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nette\Utils\Random;


class AppointmentService
{
    public function __construct(
        protected AppointmentRepository $repository,
        protected MaintenanceRepository $maintenanceRepository,
        protected WorkScheduleSettingsRepository $settingsRepository,
        protected WorkScheduleWorkRepository $workRepository,
        protected WorkScheduleBreakRepository $breakRepository
    ) {}

    /**
     * @throws TimeIsNotValidException
     */
    public function create(array $data, ?string $orderNumber = null): array
    {
        $settings = $this->settingsRepository->whereFirst(['specialist_id' => $data['specialist_id']]);
        $orderNumber = $orderNumber ?? Random::generate(5, '0-9');
        $start = Carbon::parse($data['time']['start']);
        $output = [];
        foreach ($data['services'] as $maintenance) {
            $maintenance = $this->maintenanceRepository->whereFirst([
                'id' => $maintenance['id']
            ]);
            $appointment = [
                'dummy_client_id' => $data['client']['type'] == 'dummy' ? $data['client']['id'] : null,
                'client_id' => $data['client']['type'] == 'client' ? $data['client']['id'] : null,
                'specialist_id' => $data['specialist_id'],
                'date' => $data['date']['value'],
                'maintenance_id' => $maintenance['id'],
                'time_start' => $start->format('H:i'),
                'time_end' => $start->addMinutes($maintenance->duration)->format('H:i'),
                'order_number' => $orderNumber,
                'status' => $settings->confirmation ? 'confirmed' : 'unconfirmed'
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

    public function delete(string $orderNumber): bool
    {
        $records = $this->repository->whereGet([
            'order_number' => $orderNumber
        ]);
        foreach ($records as $record) {
            $record->delete();
        }

        return true;
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

    /**
     * @throws SpecialistNotFoundException
     */
    public function getAllByDay(string $date, ?int $specialistId = null): Collection
    {
        if (is_null($specialistId)) {
            $specialistId = AuthHelper::getSpecialistIdFromAuth();
        }
        $output = collect();
        $appointments = $this->convertToOrderType(collect($this->repository->getAllByDate($date, $specialistId)));
        $breaks = $this->convertBreakToOrderType(collect($this->breakRepository->getBreaksForDay($date, true, $specialistId)));

        $appointments = $appointments->merge($breaks);
        $output->appointments = $appointments;
        $times = WorkScheduleWorkRepository::getWorkDay($date, $specialistId) ?? null;
        if (!is_null($times)) {
            $timesobj = new \StdClass;
            $timesobj->start = Carbon::parse($date . $times[0])->toISOString();
            $timesobj->end = Carbon::parse($date . $times[1])->toISOString();
        } else {
            $timesobj = null;
        }
        $output->workSchedule = $timesobj;
        $output->smartSchedule = WorkScheduleSettings::where([
            'specialist_id' => $specialistId
        ])->first()->smart_schedule;
        return $output;
    }

    public function getSvgForPeriod(array $dates): array
    {
        $output = [];
        $settings = $this->settingsRepository->mySettings();
        foreach ($dates as $date) {
            $days = TimeHelper::getMonthInterval($date);
            if ($settings->type == 'sliding') {
                $days = array_filter($days, function ($a) use ($settings) {
                    return Carbon::parse($settings->start_from) <= Carbon::parse($a);
                });
            }
            foreach ($days as $day) {
                $schedule = WorkScheduleWorkRepository::getWorkDay($day);
                $minMax = $this->getMinMaxTimes($day);
                if (is_null($schedule)) {
                    continue;
                }
                $output[$day] = $this->getSvgForDate($day, $minMax[0], $minMax[1], $schedule[0], $schedule[1]);
            }
        }

        return $output;
    }

    public function getSvgForDate(
        string $date, string $minTime, string $maxTime, string $startDay, string $endDay
    ): array
    {
        $appointments = $this->repository->getAllByDate($date);
        $breaks = $this->breakRepository->getBreaksForDay($date);
        return $this->convertToScheduleType($appointments, $breaks, $minTime, $maxTime, $startDay, $endDay);
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

    private function convertBreakToOrderType(Collection $breaks): Collection
    {
        $output = [];
        foreach ($breaks as $break) {
            $item = [
                'date' => $break->date,
                'status' => 'break',
                'interval' => TimeHelper::getTimeInterval($break->start, $break->end),
            ];
            $output[] = $item;
        }

        return collect($output);
    }

    protected function convertToOrderType(Collection $appointments): Collection
    {
        $usedOrders = [];
        $output = [];
        foreach ($appointments as $appointment) {
            $order = $appointment->order_number;
            if (in_array($order, $usedOrders)) {
                continue;
            }
            $records = $appointments->where('order_number', '=', $order);
            $minTime = min(array_column($records->toArray(), 'time_start'));
            $maxTime = max(array_column($records->toArray(), 'time_end'));
            $item = [
                'order_number' => $order,
                'date' => [
                    'label' => Carbon::parse($records->first()->date)->format('d.m.Y'),
                    'value' => $records->first()->date
                ],
                'status' => $records->first()->status,
                'interval' => TimeHelper::getTimeInterval($minTime, $maxTime),
                'services' => [],
                'client' => [
                    'id' => $records->first()->client?->id ?? $records->first()->dummyClient?->id,
                    'name' => $records->first()->client?->name ?? $records->first()->dummyClient?->name,
                    'surname' => $records->first()->client?->surname ?? $records->first()->dummyClient?->surname,
                    'phone_number' => $records->first()->client?->user->phone_number
                        ?? $records->first()->dummyClient?->phone_number,
                    'photo' => ImageHelper::getAssetFromFilename($records->first()->client?->avatar?->url
                        ?? $records->first()->dummyClient?->avatar?->url),
                    'discount' => $records->first()?->dummyClient?->discount * 100 ?? null,
                    'type' => is_null($records->first()?->client) ? 'dummy' : 'client'
                ],
                'time' => [
                    'start' => Carbon::parse($minTime)->format('H:i'),
                    'end' => Carbon::parse($maxTime)->format('H:i')
                ]
            ];
            foreach ($records as $record) {
                $item['services'][] = [
                    'id' => $record->maintenance->id,
                    'title' => $record->maintenance->title,
                    'price' => [
                        'label' => str($record->maintenance->price)->value(),
                        'value' => $record->maintenance->price
                    ],
                    'duration' => [
                        'label' => str($record->maintenance->duration)->value(),
                        'value' => $record->maintenance->duration
                    ],
                    'interval' => TimeHelper::getTimeInterval($record->time_start, $record->time_end)
                ];
            }
            $usedOrders[] = $order;
            $output[] = collect($item);
        }

        return collect($output);
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

    private function convertToScheduleType(
        $appointments, $breaks, string $minTime, string $maxTime, string $startDay, string $endDay
    ): array
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
        $interval = TimeHelper::getTimeIntervalAsFreeAppointment($minTime, $maxTime, $startDay, $endDay);
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
        $svg[] = [
            'stroke' => SvgHelper::getColorFromType('break'), // cause of white
            'strokeDasharray' => 30,
            'strokeDashoffset' => -70
        ];
        return $svg;
    }
}
