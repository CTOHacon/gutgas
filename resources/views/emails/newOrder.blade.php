<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table style="width: 100%;
    border: 1px solid black;
    border-collapse: collapse;">
        <tr>
            <td style="border: 1px solid black;padding: 10px;">Імʼя</td>
            <td style="border: 1px solid black;padding: 10px;">{{ $client_name }}</td>
        </tr>

        <tr>
            <td style="border: 1px solid black;padding: 10px;">Пошта</td>
            <td style="border: 1px solid black;padding: 10px;">{{ $client_email }}</td>
        </tr>

        <tr>
            <td style="border: 1px solid black;padding: 10px;">Телефон</td>
            <td style="border: 1px solid black;padding: 10px;">{{ $client_phone }}</td>
        </tr>

        @isset($client_message)
            <tr>
                <td style="border: 1px solid black;padding: 10px;">Повідомлення</td>
                <td style="border: 1px solid black;padding: 10px;">{{ $client_message }}</td>
            </tr>
        @endisset
    </table>

    <table style="width: 100%;
    border: 1px solid black;
    border-collapse: collapse;">
        <thead style="border-bottom: 3px solid black;">
            <tr>
                <th style="border: 1px solid black;padding: 10px;">id</th>
                <th style="border: 1px solid black;padding: 10px;">Назва</th>
                <th style="border: 1px solid black;padding: 10px;">SKU</th>
                <th style="border: 1px solid black;padding: 10px;">Кількість</th>
                <th style="border: 1px solid black;padding: 10px;">Загалом</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart_content as $cart_item)
                <tr>
                    <td style="border: 1px solid black;padding: 10px;">{{ $cart_item['id'] }}</td>
                    <td style="border: 1px solid black;padding: 10px;">{{ $cart_item['name']['ua'] }}</td>
                    <td style="border: 1px solid black;padding: 10px;">{{ $cart_item['sku'] }}</td>
                    <td style="border: 1px solid black;padding: 10px;">{{ $cart_item['quantity'] }}</td>
                    <td style="border: 1px solid black;padding: 10px;">
                        {{ $cart_item['price'] * $cart_item['quantity'] }} ₴</td>
                </tr>
        </tbody>
        @endforeach
    </table>
</body>

</html>
