<?php

namespace App\Services;

use App\Helpers\ArrayHelper;
use App\Helpers\TimeHelper;
use App\Http\Resources\SpecialistData\MaintenanceResource;
use App\Repositories\MaintenanceRepository;
use App\Repositories\WorkSchedule\WorkScheduleBreakRepository;
use App\Repositories\WorkSchedule\WorkScheduleWorkRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpecialistDataService
{
    public function __construct(
        protected WorkScheduleBreakRepository $breakRepository,
        protected WorkScheduleWorkRepository $workRepository,
        protected WorkScheduleService $workScheduleService,
        protected AppointmentService $appointmentService,
        protected MaintenanceRepository $maintenanceRepository
    ){}

    public function getFreeHours(int $specialistId, string $dateFromMonth): ?array
    {
        $monthDates = TimeHelper::getMonthInterval($dateFromMonth);
        $output = [];
        foreach ($monthDates as $date) {
            list($startDay, $endDay) = WorkScheduleWorkRepository::getWorkDay($date, $specialistId);
            if (is_null($startDay) || is_null($endDay)) {
                continue;
            }
            $breaks = $this->breakRepository->getBreaksForDay($date, false, $specialistId);
            $appointments = $this->appointmentService->getAllByDay($date, $specialistId)->appointments;
            $appointmentsInterval = [];
            foreach ($appointments as $appointment) {
                $appointmentsInterval[] = $appointment['interval'];
            }
            $interval = TimeHelper::getTimeInterval($startDay, $endDay);
            $output[] = [
                $date => ArrayHelper::arrayWithoutIntersections($interval, [...$appointmentsInterval, ...$breaks])
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
}
