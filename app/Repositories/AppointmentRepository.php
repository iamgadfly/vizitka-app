<?php

namespace App\Repositories;

use App\Models\Appointment;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppointmentRepository extends Repository
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

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

    public function getAllByOrderNumber(string $orderNumber)
    {
        return $this->model::where([
            'order_number' => $orderNumber
        ])->get();
    }

    public function getAllByDate(string $date)
    {

        return $this->model::where([
            'date' => Carbon::parse($date),
            'specialist_id' => auth()->user()->specialist->id
        ])->get();
    }
}