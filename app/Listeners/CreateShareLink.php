<?php

namespace App\Listeners;

use App\Events\SpecialistCreatedEvent;
use App\Models\Specialist;
use App\Services\ShareService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateShareLink implements ShouldQueue
{
    use InteractsWithQueue;

    public $afterCommit = true;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected ShareService $shareService
    ){}

    /**
     * Handle the event.
     *
     * @param SpecialistCreatedEvent $event
     * @return void
     */
    public function handle(SpecialistCreatedEvent $event): void
    {
        $url = \URL::route('client.contactBook.create', ['id' => $event->specialist->id], false);
        $this->shareService->createShortlink($url, Specialist::class, $event->specialist->id);
    }
}
