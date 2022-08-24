<?php

namespace App\Listeners;

use App\Events\SpecialistCreatedEvent;
use App\Exceptions\ClientNotFoundException;
use App\Exceptions\RecordIsAlreadyExistsException;
use App\Services\ContactBookForClientService;
use App\Services\DummyBusinessCardService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBusinessCards implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected DummyBusinessCardService $businessCardService,
        protected ContactBookForClientService $contactBookForClientService
    ){}

    /**
     * Handle the event.
     *
     * @param SpecialistCreatedEvent $event
     * @return void
     * @throws ClientNotFoundException
     * @throws RecordIsAlreadyExistsException
     */
    public function handle(SpecialistCreatedEvent $event): void
    {
        $specialist = $event->specialist;
        $dummyCards = $this->businessCardService->getByPhoneNumber($specialist->user->phone_number);

        foreach ($dummyCards as $dummyCard) {
            $clientId = $dummyCard->client->id;
            $this->contactBookForClientService->create($specialist->id, $clientId);
            $dummyCard->delete();
        }
    }
}
