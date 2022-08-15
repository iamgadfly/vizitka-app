<?php

namespace App\Repositories;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AppointmentRepository
 *
 * @package App\Repositories
 *
 * @method Collection<Appointment> whereGet(array $condition)
 * @method Appointment whereFirst(array $condition)
 */
class AppointmentRepository extends Repository
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $orderNumber
     * @return bool
     */
    public function confirm(string $orderNumber): bool
    {
        $items = $this->getAllByOrderNumber($orderNumber);
        if (empty($items)) {
            throw new  NotFoundHttpException;
        }
        foreach ($items as $item) {
            $item->status = 'confirmed';
            $item->save();
        }
        return true;
    }

    /**
     * @param string $orderNumber
     * @return bool
     */
    public function skipped(string $orderNumber): bool
    {
        $items = $this->getAllByOrderNumber($orderNumber);
        if (empty($items)) {
            throw new  NotFoundHttpException;
        }
        foreach ($items as $item) {
            $item->status = 'skipped';
            $item->save();
        }
        return true;
    }

    /**
     * @param string $orderNumber
     * @return Collection|null
     */
    public function getAllByOrderNumber(string $orderNumber): ?Collection
    {
        return $this->model::where([
            'order_number' => $orderNumber
        ])->get();
    }

    /**
     * @param string $date
     * @param int|null $specialistId
     * @return Collection
     * @throws SpecialistNotFoundException
     */
    public function getAllByDate(string $date, ?int $specialistId = null): Collection
    {
        if (is_null($specialistId)) {
            $specialistId = AuthHelper::getSpecialistIdFromAuth();
        }
        return $this->model::where([
            'date' => Carbon::parse($date),
            'specialist_id' => $specialistId
        ])->whereNot('status', 'cancelled')->get();
    }
}
