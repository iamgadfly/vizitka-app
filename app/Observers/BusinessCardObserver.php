<?php

namespace App\Observers;

use App\Models\BusinessCard;
use App\Services\GeocodeService;
use Geocoder\Exception\Exception;

class BusinessCardObserver
{
    /**
     * Handle the BusinessCard "created" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function created(BusinessCard $businessCard)
    {
        $this->getCoordinates($businessCard);
    }

    /**
     * Handle the BusinessCard "updated" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function updated(BusinessCard $businessCard)
    {
        $this->getCoordinates($businessCard);
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

    /**
     * @param BusinessCard $businessCard
     * @return void
     */
    private function getCoordinates(BusinessCard $businessCard): void
    {
        if (is_null($businessCard->address)) {
            return;
        }
        try {
            $coordinates = GeocodeService::fromAddress($businessCard->address)->first()->getCoordinates();
            $businessCard->latitude = $coordinates->getLatitude();
            $businessCard->longitude = $coordinates->getLongitude();
        } catch (Exception $e) {
            $businessCard->latitude = null;
            $businessCard->longitude = null;
        }
        $businessCard->save();
    }
}
