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

    public function confirm(int $id)
    {
        $item = $this->getById($id) ?? throw new NotFoundHttpException;
        $item->status = 'confirmed';
        $item->save();
        return $item;
    }

    public function skipped(int $id)
    {
        $item = $this->getById($id) ?? throw new  NotFoundHttpException;
        $item->status = 'skipped';
        $item->save();
        return $item;
    }

    public function getAllByDate(string $date)
    {
        return $this->model::where([
            'date' => Carbon::parse($date),
            'specialist_id' => auth()->user()->specialist->id
        ])->get();
    }
}
