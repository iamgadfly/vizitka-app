<?php

namespace App\Listeners;

use App\Events\WorkScheduleSettingsCreated;
use App\Repositories\WorkSchedule\WorkScheduleDayRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateWorkScheduleDays implements ShouldQueue
{
    use InteractsWithQueue;

    public $afterCommit = true;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected WorkScheduleDayRepository $dayRepository
    ){}

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WorkScheduleSettingsCreated $event)
    {
        $workScheduleSettings = $event->scheduleSettings;
        $type = $workScheduleSettings->type;
        if ($type == 'sliding') {
            $this->dayRepository->fillDaysForSlidingType(
                $workScheduleSettings->id, $workScheduleSettings->workdays_count, $workScheduleSettings->weekdays_count
            );
        } else {
            $this->dayRepository->fillDaysNotForSlidingType($workScheduleSettings->id);
        }
    }
}
