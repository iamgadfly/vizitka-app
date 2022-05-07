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
          'pin_exception' => 'Неврно введен PIN код',
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
    'maintenance' => [
        'exceptions' => [
            'specialist' => [
                'maintenance_settings' => 'Настройки услуг уже существуют'
            ]
        ],
    ],
    'work_schedule' => [
        'exceptions' => [
            'specialist' => [
                'work_schedule_settings' => 'Настройки рабочего графика уже существуют'
            ]
        ],
        'rules' => [
            'specialist' => [
                'weekday' => 'Недопустимое значение дня недели',
                'work_schedule' => 'Массив невалидный'
            ]
        ]
    ],
    'other' => [
        'rules' => [
            'array_is_not_valid' => 'Массив невалидный',
            'time_is_not_valid' => 'Данный отрезок времени занят'
        ]
    ]
];
