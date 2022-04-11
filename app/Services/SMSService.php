<?php

namespace App\Services;

use App\Exceptions\SMSNotSentException;
use GuzzleHttp\Client as GuzzleClient;

class SMSService
{
    protected ?string $hostSmsUrl;
    protected ?string $login;
    protected ?string $password;
    protected ?string $sender;

    public function __construct()
    {
        $this->hostSmsUrl = config('custom.sms_host');
        $this->login = config('custom.sms_login');
        $this->password = config('custom.sms_password');
        $this->sender = config('custom.sms_sender');
    }

    /**
     * @throws SMSNotSentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSms(string $text, string $phone)
    {
        $phone = str($phone)->replace('+', '')->value();

        $client = new GuzzleClient();
        $body = [
            'security' => [
                'login' => $this->login,
                'password' => $this->password
            ],
            'type' => 'sms',
            'message' => [[
                'type' => 'sms',
                'sender' => $this->sender,
                'text' => $text,
                'abonent' => [[
                    'phone' => $phone,
                    'number_sms' => 1,
                ]],
            ]]
        ];
        $response = $client->post($this->hostSmsUrl, [
            'json' => $body,
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ]
        ]);

        $response =  json_decode($response->getBody(), true);
        if (isset($response['sms'][0]['error'])) {
            throw new SMSNotSentException;
        }
    }
}
