<?php

namespace App\Observers;

use App\Models\BusinessCard;
use App\Services\GeocodeService;

class BusinessCardObserver
{
    public function creating(BusinessCard $businessCard)
    {
        try {
            $coordinates = GeocodeService::fromAddress($businessCard->address)->first()->getCoordinates();
            $businessCard->latitude = $coordinates->getLatitude();
            $businessCard->longitude = $coordinates->getLongitude();
        } catch (\Exception) {
        }
    }
    /**
     * Handle the BusinessCard "created" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function created(BusinessCard $businessCard)
    {
        //
    }

    /**
     * Handle the BusinessCard "updated" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function updated(BusinessCard $businessCard)
    {
        $coordinates = GeocodeService::fromAddress($businessCard->address);
        $businessCard->latitude = $coordinates->latitude;
        $businessCard->longitude = $coordinates->longitude;
    }

    /**
     * Handle the BusinessCard "deleted" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function deleted(BusinessCard $businessCard)
    {
        //
    }

    /**
     * Handle the BusinessCard "restored" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function restored(BusinessCard $businessCard)
    {
        //
    }

    /**
     * Handle the BusinessCard "force deleted" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function forceDeleted(BusinessCard $businessCard)
    {
        //
    }
}
