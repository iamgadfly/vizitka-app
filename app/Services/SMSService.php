<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;

class SMSService
{
    protected ?string $hostSmsUrl;
    protected ?string $login;
    protected ?string $password;

    public function __construct()
    {
        $this->hostSmsUrl = env('SMS_SERVICE_HOST');
        $this->login = env('SMS_SERVICE_LOGIN');
        $this->password = env('SMS_SERVICE_PASSWORD');
    }

    public function sendSms(string $text, string $phone)
    {
        $client = new GuzzleClient();
        $body = [
            'security' => [
                'login' => $this->login,
                'password' => $this->password
            ],
            'type' => 'sms',
            'message' => [[
                'type' => 'sms',
                'sender' => 'Test Sender',
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

        return json_decode($response->getBody(), true);
    }
}
