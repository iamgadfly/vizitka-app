<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getAssetFromFilename(string $filename): string
    {
        return asset(\Storage::url($filename));
    }
}
