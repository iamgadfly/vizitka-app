<?php

namespace App\Jobs;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $images = Image::all();
        $nowDate = Carbon::now();
        foreach ($images as $image) {
            if ($image->deleted_at <= $nowDate) {
                \Storage::disk('public')->delete($image->url);
                $image->delete();
            }
        }
    }
}
