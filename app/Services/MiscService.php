<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;

class MiscService
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCountries()
    {
        $cache = \Cache::get('countries');

        if (!is_null($cache)) {
            return json_decode($cache, true);
        }

        $client = new Client();
        $response = $client->request('GET', 'https://restcountries.com/v3.1/all', [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        $body = json_decode($response->getBody()->getContents());
        $output = [];
        foreach ($body as $item) {
            $country = [
                'name' => $item->translations->rus->common,
                'flag' => $item->flags->png,
            ];
            if (count((array) $item->idd) > 0) {
                if (count($item->idd->suffixes) > 1) {
                    $country['code'] = $item->idd->root;
                } else {
                    $country['code'] = $item->idd->root . $item->idd->suffixes[0];
                }
            } else {
                continue;
            }

            $output[] = $country;
        }
        \Cache::put('countries', json_encode($output), Carbon::now()->addMonth());

        return $output;
    }
}
