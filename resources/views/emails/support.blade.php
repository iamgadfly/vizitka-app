<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Описание страницы" />
</head>
<body>
    Обращение от {{ $data->fullName }}
    <br>
    <br>
    Телефон: {{ $data->phoneNumber }}
    <br>
    <br>
    {{ $data->text }}
    <br>
    @if($data->file)
        <img src="{{ $data->file }}"/>
    @endif
</body>
</html>
