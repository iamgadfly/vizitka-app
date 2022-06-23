<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getAssetFromFilename(?string $filename): ?string
    {
        if (is_null($filename)) {
            return null;
        }
        return asset(\Storage::url($filename));
    }
}
