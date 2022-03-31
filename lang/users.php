<?php

return [
    'auth' => [
        'specialist' => [
            'pin_exception' => 'PIN is not valid'
        ],
        'client' => [

        ],
        'validation' => [
            'phone_number' => [
                'exists' => 'Phone number does not exists',
                'unique' => 'Phone number exists'
            ],
            'type' => [
                'in' => 'Parameter type must be specialist or client'
            ]
        ]
    ],
    'geocoder' => [
        'validation' => [
            'coordinates' => [
                'required' => 'Parameter coordinates required'
            ]
        ]
    ]
];
