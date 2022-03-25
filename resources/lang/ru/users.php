<?php

return [
  'auth' => [
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
];
