<?php

namespace App\Repositories\WorkSchedule;

use App\Models\WorkScheduleSettings;

/**
 * @method WorkScheduleSettings create(array $data)
 */
class WorkScheduleSettingsRepository extends Repository
{
    public function __construct(WorkScheduleSettings $model)
    {
        parent::__construct($model);
    }

    public function mySettings()
    {
        $model = $this->model::where('specialist_id', auth()->user()->specialist->id);
        return $model->first();
    }

    public function settingsForSpecialist(int $specialistId)
    {
        $model = $this->model::where('specialist_id', $specialistId);
        return $model->first();
    }
}
