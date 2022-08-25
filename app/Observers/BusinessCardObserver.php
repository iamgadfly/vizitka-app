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
        $this->setCoordinates($businessCard)->save();
    }

    /**
     * Handle the BusinessCard "updated" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function updated(BusinessCard $businessCard)
    {
        $this->setCoordinates($businessCard)->save();
    }

    /**
     * @param BusinessCard $businessCard
     * @return BusinessCard
     */
    private function setCoordinates(BusinessCard $businessCard): BusinessCard
    {
        if (is_null($businessCard->address)) {
            return $businessCard;
        }
        try {
            $coordinates = GeocodeService::fromAddress($businessCard->address)->first()->getCoordinates();
            $businessCard->latitude = $coordinates->getLatitude();
            $businessCard->longitude = $coordinates->getLongitude();
        } catch (Exception $e) {
            $businessCard->latitude = 0;
            $businessCard->longitude = 0;
        }

        return $businessCard;
    }
}
