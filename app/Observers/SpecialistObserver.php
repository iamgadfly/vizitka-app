<?php

namespace App\Observers;

use App\Events\SpecialistCreatedEvent;
use App\Models\Specialist;

class SpecialistObserver
{
    /**
     * Handle the Specialist "created" event.
     *
     * @param  \App\Models\Specialist  $specialist
     * @return void
     */
    public function created(Specialist $specialist)
    {
        event(new SpecialistCreatedEvent($specialist));
    }

    /**
     * Handle the Specialist "updated" event.
     *
     * @param  \App\Models\Specialist  $specialist
     * @return void
     */
    public function updated(Specialist $specialist)
    {
        //
    }

    /**
     * Handle the Specialist "deleted" event.
     *
     * @param  \App\Models\Specialist  $specialist
     * @return void
     */
    public function deleted(Specialist $specialist)
    {
        //
    }

    /**
     * Handle the Specialist "restored" event.
     *
     * @param  \App\Models\Specialist  $specialist
     * @return void
     */
    public function restored(Specialist $specialist)
    {
        //
    }

    /**
     * Handle the Specialist "force deleted" event.
     *
     * @param  \App\Models\Specialist  $specialist
     * @return void
     */
    public function forceDeleted(Specialist $specialist)
    {
        //
    }
}
