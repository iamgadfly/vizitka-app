<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRService
{
    public static function generate(string $text)
    {
        return QrCode::encoding('UTF-8')->size(500)->generate($text);
    }
}
