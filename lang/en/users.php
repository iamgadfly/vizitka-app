<?php

return [
    'auth' => [
        'exceptions' => [
            'sms_not_sent' => 'Something went wrong',
            'invalid_password' => 'Password is invalid',
            'user_not_verified' => 'User is not verified',
            'invalid_login' => 'Login is invalid',
            'unauthorized' => 'Unauthorized',
            'user_not_found' => 'User not found',
            'verified' => 'User has already been verified',
            'verification_code_inst_valid' => 'Verification code is not valid',
            'too_many_login' => 'Too many login attempts. Try again in 15 minutes'
        ],
        'specialist' => [
            'pin_exception' => 'PIN is not valid',
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
    'maintenance' => [
        'exceptions' => [
            'specialist' => [
                'maintenance_settings' => 'Maintenance settings is already existing'
            ]
        ],
        'rules' => [
            'specialist' => [
                'maintenance' => 'Array is not valid',
            ]
        ]
    ],
    'work_schedule' => [
        'exceptions' => [
            'specialist' => [
                'work_schedule_settings' => 'Work schedule settings is already existing'
            ]
        ],
        'rules' => [
            'specialist' => [
                'weekday' => 'Weekday is not correct'
            ]
        ]
    ],
    'other' => [
        'rules' => [
            'array_is_not_valid' => 'Array is not valid'
        ]
    ]
];
