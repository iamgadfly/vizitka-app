<?php

namespace App\Services\Client;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\SpecialistNotFoundException;
use App\Exceptions\TimeIsNotValidException;
use App\Helpers\AuthHelper;
use App\Helpers\ImageHelper;
use App\Http\Resources\SpecialistResource;
use App\Repositories\AppointmentRepository;
use App\Repositories\MaintenanceRepository;
use App\Services\AppointmentService as BaseAppointmentService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nette\Utils\Random;


class AppointmentService extends BaseAppointmentService
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
        foreach ($data['maintenances'] as $maintenanceId) {
            $maintenance = $this->maintenanceRepository->whereFirst([
                'id' => $maintenanceId
            ]);
            $appointment = [
                'dummy_client_id' => null,
                'client_id' => $data['client_id'],
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
            $specialistId = $appointment->specialist->id;
        }
        $data['specialist_id'] = $specialistId;
        return $this->create($data, $data['order_number']);
    }

    /**
     * @throws ClientNotFoundException
     * @throws SpecialistNotFoundException
     */
    public function getMyHistory(?int $clientId = null): Collection
    {
        if (is_null($clientId)) {
            return $this->convertToOrderType($this->repository->whereGet([
                'client_id' => AuthHelper::getClientIdFromAuth()
            ]));
        }
        return $this->convertToOrderType($this->repository->whereGet([
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
            'client_id' => $clientId
        ]));
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
            $item = [
                'order_number' => $order,
                'date' => $records->first()->date,
                'status' => $records->first()->status,
                'services' => [],
                'specialist' => new SpecialistResource($records->first()->specialist)
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
                    'start' => Carbon::parse($record->time_start)->format('H:i'),
                    'end' => Carbon::parse($record->time_end)->format('H:i')
                ];
            }
            $usedOrders[] = $order;
            $output[] = collect($item);
        }

        return collect($output);
    }

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
