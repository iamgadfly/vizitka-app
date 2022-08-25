<?php

namespace App\Observers;

use App\Models\BusinessCard;
use App\Services\GeocodeService;
use Geocoder\Exception\Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BusinessCardObserver implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Handle the BusinessCard "created" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function created(BusinessCard $businessCard)
    {
        $this->setCoordinates($businessCard);
    }

    /**
     * Handle the BusinessCard "updated" event.
     *
     * @param  \App\Models\BusinessCard  $businessCard
     * @return void
     */
    public function updated(BusinessCard $businessCard)
    {
        $this->setCoordinates($businessCard);
    }

    /**
     * @param BusinessCard $businessCard
     * @return void
     */
    private function setCoordinates(BusinessCard $businessCard): void
    {
        if (is_null($businessCard->address)) {
            return;
        }
        try {
//            $coordinates = GeocodeService::fromAddress($businessCard->address)->first()->getCoordinates();
//            $data = app('gecooder')->geocode($businessCard->address);
//            dd($data);
//            $businessCard->latitude = $coordinates->getLatitude();
//            $businessCard->longitude = $coordinates->getLongitude();
        } catch (Exception $e) {
            $businessCard->latitude = 0;
            $businessCard->longitude = 0;
        }

        $businessCard->save();
    }
}
