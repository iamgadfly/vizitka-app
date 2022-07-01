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

        $share->hash = str(md5(Carbon::now()->toString()))->substr(9, 10);
        $share->deactivated_at = Carbon::now()->addMonths(3);
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
