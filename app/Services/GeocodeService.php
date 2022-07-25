<?php

namespace App\Services;

use Geocoder\Exception\Exception;
use Geocoder\Provider\Yandex\Yandex;
use Geocoder\StatefulGeocoder;
use Http\Client\Curl\Client;

class GeocodeService
{
    /**
     * @throws Exception
     */
    public static function fromAddress(string $address): \Geocoder\Collection
    {
        $geocoder = self::getGeocoder();

        return $geocoder->geocode($address);
    }

    private static function getGeocoder(): StatefulGeocoder
    {
        $httpClient = new Client();
        $provider = new Yandex($httpClient, null, config('custom.yandex_maps_api_key'));
        return new StatefulGeocoder($provider, config('app.locale'));
    }
}
