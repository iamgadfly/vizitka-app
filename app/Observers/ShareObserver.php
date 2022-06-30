<?php

namespace App\Observers;

use App\Models\Share;
use Carbon\Carbon;
use Faker\Core\Uuid;
use Nette\Utils\Random;

class ShareObserver
{
    public function creating(Share $share)
    {
//        $share->hash = Random::generate('6', 'az-AZ0-9');

        dd(\Hash::make(Carbon::now()->toString()));
        $share->hash = 1;
        $share->save();
    }

    /**
     * Handle the Share "updated" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function updated(Share $share)
    {
        //
    }

    /**
     * Handle the Share "deleted" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function deleted(Share $share)
    {
        //
    }

    /**
     * Handle the Share "restored" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function restored(Share $share)
    {
        //
    }

    /**
     * Handle the Share "force deleted" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function forceDeleted(Share $share)
    {
        //
    }
}
