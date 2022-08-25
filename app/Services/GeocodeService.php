<?php

namespace App\Services;

use Geocoder\Exception\Exception;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\Nominatim\Nominatim;
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
        $provider = Nominatim::withOpenStreetMapServer(
            $httpClient, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36'
        );
        return new StatefulGeocoder($provider, config('app.locale'));
    }
}
