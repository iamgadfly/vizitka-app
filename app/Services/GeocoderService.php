<?php

namespace App\Services;

use Geocoder\Provider\Yandex\Yandex;
use Geocoder\StatefulGeocoder;
use Http\Client\Curl\Client;

class GeocoderService
{
    protected Client $client;
    protected Yandex $provider;
    protected StatefulGeocoder $geocoder;

    public function __construct()
    {
        $this->client = new Client();
        $this->provider = new Yandex($this->client, null, env('YANDEX_MAPS_API_KEY'));
        $this->geocoder = new StatefulGeocoder($this->provider, 'ru');
    }

    public function fromCoordinates(string $latitude, string $longitude)
    {
        $data = $this->geocoder->reverse($latitude, $longitude)->first();
        return [
            'country' => $data->getCountry()->getName(),
            'city' => $data->getLocality(),
            'street' => $data->getStreetName(),
            'streetNumber' => $data->getStreetNumber()
        ];
    }
}
