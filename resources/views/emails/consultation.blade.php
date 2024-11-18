<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table style="width:100%; border:1px solid black; border-collapse:collapse;">
        @isset($name)
            <tr>
                <td style="border:1px solid black; padding:10px;">Імʼя</td>
                <td style="border:1px solid black; padding:10px;">{{ $name }}</td>
            </tr>
        @endisset

        @isset($email)
            <tr>
                <td style="border:1px solid black; padding:10px;">Пошта</td>
                <td style="border:1px solid black; padding:10px;">{{ $email }}</td>
            </tr>
        @endisset

        @isset($phone)
            <tr>
                <td style="border:1px solid black; padding:10px;">Телефон</td>
                <td style="border:1px solid black; padding:10px;">{{ $phone }}</td>
            </tr>
        @endisset

        @isset($question)
            <tr>
                <td style="border:1px solid black; padding:10px;">Повідомлення</td>
                <td style="border:1px solid black; padding:10px;">{{ $question }}</td>
            </tr>
        @endisset
    </table>
</body>

</html>
