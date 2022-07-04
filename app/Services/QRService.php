<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRService
{
    public static function generate(string $text)
    {
        return QrCode::format('png')
            ->merge(storage_path('app/public/images/default/qr_centre.png'), 0.3, true)
            ->errorCorrection('H')
            ->size(500)
            ->generate($text);
    }
}
