<?php

namespace App\Providers;

use App\Events\SpecialistCreatedEvent;
use App\Events\WorkScheduleSettingsCreated;
use App\Listeners\CreateShareLink;
use App\Listeners\CreateWorkScheduleDays;
use App\Listeners\UpdateBusinessCards;
use App\Models\BusinessCard;
use App\Models\Share;
use App\Models\Specialist;
use App\Models\WorkScheduleSettings;
use App\Observers\BusinessCardObserver;
use App\Observers\ShareObserver;
use App\Observers\SpecialistObserver;
use App\Observers\WorkScheduleSettingsObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        // Observers
        Share::observe(ShareObserver::class);
        BusinessCard::observe(BusinessCardObserver::class);

        // Event listeners
        Event::listen(SpecialistCreatedEvent::class, CreateShareLink::class);
        Event::listen(SpecialistCreatedEvent::class, UpdateBusinessCards::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
