<?php

return [
  'auth' => [
      'exceptions' => [
          'sms_not_sent' => 'Что-то пошло не так',
          'invalid_password' => 'Пароль неверный',
          'user_not_verified' => 'Пользователь не верифицирован',
          'invalid_login' => 'Логин неправильный',
          'unauthorized' => 'Пользователь не авторизован',
          'user_not_found' => 'Пользователь не найден',
          'verified' => 'Пользователь уже верифицирован',
          'verification_code_inst_valid' => 'Код верификации неправильный',
          'too_many_login' => 'Слишком много попыток входа. Попробуйте еще раз через 15 минут'
      ],
      'specialist' => [
          'pin_exception' => 'Неврно введен PIN код'
      ],
      'client' => [

      ],
      'validation' => [
          'phone_number' => [
              'exists' => 'Номер телефона не существует',
              'unique' => 'Номер телефона уже существует'
          ],
          'type' => [
              'in' => 'Параметер type должен быть specialist или client'
          ]
      ]
  ],
    'geocoder' => [
        'validation' => [
            'coordinates' => [
                'required' => 'Параметр coordinates обязателен'
            ]
        ]
    ]
];
