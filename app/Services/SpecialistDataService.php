<?php

namespace App\Services;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\TimeHelper;
use App\Http\Resources\SpecialistData\MaintenanceResource;
use App\Repositories\MaintenanceRepository;
use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use App\Repositories\WorkSchedule\WorkScheduleWorkRepository;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpecialistDataService
{
    public function __construct(
        protected WorkScheduleBreakRepository $breakRepository,
        protected WorkScheduleWorkRepository $workRepository,
        protected WorkScheduleService $workScheduleService,
        protected AppointmentService $appointmentService,
        protected MaintenanceRepository $maintenanceRepository,
        protected PillDisableService $pillDisableService
    ){}

    /**
     * @throws SpecialistNotFoundException
     */
    public function getFreeHours(int $specialistId, string $dateFromMonth, int $sum): ?array
    {
        $monthDates = TimeHelper::getMonthIntervalWithOutPastDates($dateFromMonth);
        $output = [];
        foreach ($monthDates as $date) {
            list($startDay, $endDay) = WorkScheduleWorkRepository::getWorkDay($date, $specialistId);
            $interval = TimeHelper::getTimeInterval($startDay, $endDay);

            if (empty($interval)) {
                continue;
            }

            $breaks = $this->breakRepository->getBreaksForDay($date, false, $specialistId);
            $breaks = $this->getBreaksAsInterval($breaks);

            $appointments = $this->appointmentService->getAllByDay($date, $specialistId)->appointments;
            $appointmentsInterval = [];

            //TODO: optimize that!
            $pills = $this->pillDisableService->getAllByDate($date, $specialistId);
            $pillsInterval = [];
            foreach ($pills as $pill) {
                $pillsInterval[] = TimeHelper::getFormattedTime($pill->time);
            }

            foreach ($appointments as $appointment) {
                $appointmentsInterval = array_merge($appointmentsInterval, $appointment['interval']);
            }

            $arrayWithoutIntersections = ArrayHelper::arrayWithoutIntersections(
                $interval, [...$appointmentsInterval, ...$breaks, ...$pillsInterval]
            );

            $output[] = [
                $date => $this->withoutOverlapping($arrayWithoutIntersections, $sum)
            ];
        }
        return $output;
    }

    public function getSpecialistsMaintenances(int $specialistId): AnonymousResourceCollection
    {
        $maintenances = $this->maintenanceRepository->allForCurrentUser($specialistId);
        $discount = 0;
        $maintenances->map(function ($item) use ($discount) {
            $item->discount = $discount;
        });
        return MaintenanceResource::collection($maintenances);
    }

    private function getBreaksAsInterval(array $breaks): array
    {
        if (!empty($breaks)) {
            if (is_array($breaks[0])) {
                $starts = [];
                $ends = [];
                foreach ($breaks as $break) {
                    $starts[] = $break[0];
                    $ends[] = $break[1];
                }
                return TimeHelper::getTimeInterval(min($starts), max($ends));
            }
        }
        return [];
    }

    private function withoutOverlapping(array $interval, int $sum): array {
        foreach ($interval as $index => $item) {
            $itemWithSumAsTime = Carbon::parse($item)->addMinutes($sum)->format('H:i');
            $intervalWithSum = TimeHelper::getTimeInterval($item, $itemWithSumAsTime);
            $intersection = array_intersect($interval, $intervalWithSum);
//            dd($intervalWithSum, $intersection, $interval);
            if (
                empty($intersection) ||
                (count($intervalWithSum) != count($intersection))
            ) {
                unset($interval[$index]);
            }
//            if ($index > 6) dd($intervalWithSum, $intersection, $interval);
        }

        return array_values($interval);
    }
}
