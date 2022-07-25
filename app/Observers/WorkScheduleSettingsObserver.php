<?php

namespace App\Observers;

use App\Events\WorkScheduleSettingsCreated;
use App\Models\WorkScheduleDay;
use App\Models\WorkScheduleSettings;
use App\Repositories\WorkSchedule\WorkScheduleDayRepository;

class WorkScheduleSettingsObserver
{
    public function __construct(
        protected WorkScheduleDayRepository $dayRepository
    ){}

    /**
     * Handle the WorkScheduleSettings "created" event.
     *
     * @param  \App\Models\WorkScheduleSettings  $workScheduleSettings
     * @return void
     */
    public function created(WorkScheduleSettings $workScheduleSettings)
    {
        event(new WorkScheduleSettingsCreated($workScheduleSettings));
    }

    /**
     * Handle the WorkScheduleSettings "updated" event.
     *
     * @param  \App\Models\WorkScheduleSettings  $workScheduleSettings
     * @return void
     */
    public function updated(WorkScheduleSettings $workScheduleSettings)
    {
        //
    }

    /**
     * Handle the WorkScheduleSettings "deleted" event.
     *
     * @param  \App\Models\WorkScheduleSettings  $workScheduleSettings
     * @return void
     */
    public function deleted(WorkScheduleSettings $workScheduleSettings)
    {
        //
    }

    /**
     * Handle the WorkScheduleSettings "restored" event.
     *
     * @param  \App\Models\WorkScheduleSettings  $workScheduleSettings
     * @return void
     */
    public function restored(WorkScheduleSettings $workScheduleSettings)
    {
        //
    }

    /**
     * Handle the WorkScheduleSettings "force deleted" event.
     *
     * @param  \App\Models\WorkScheduleSettings  $workScheduleSettings
     * @return void
     */
    public function forceDeleted(WorkScheduleSettings $workScheduleSettings)
    {
        //
    }
}
