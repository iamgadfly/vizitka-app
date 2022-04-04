<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function getAssetFromFilename(string $filename): string
    {
        return asset(Storage::url($filename));
    }
}
