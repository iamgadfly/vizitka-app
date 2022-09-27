<?php

return [
    'photo_path' => 'images/user_photos',

    'yandex_maps_api_key' => env('YANDEX_MAPS_API_KEY'),

    'sms_host' => env('SMS_SERVICE_HOST'),
    'sms_login' => env('SMS_SERVICE_LOGIN'),
    'sms_password' => env('SMS_SERVICE_PASSWORD'),
    'sms_sender' => env('SMS_SERVICE_SENDER'),

    'report_mail' => env('REPORTS_EMAIL'),
    'support_mail' => env('TECH_SUPPORT_EMAIL'),
    'from_mail' => env('FROM_EMAIL'),

    'vizitnica_deep_link' => env('VIZITNICA_DEEP_LINK'),
    'vizitka_deep_link' => env('VIZITKA_DEEP_LINK')
];
