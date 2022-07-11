@component('mail::message')
    # Обращение от {{ $data->fullName }}
    Телефон: {{ $data->phoneNumber }}
    {{ $data->text }}
@endcomponent
