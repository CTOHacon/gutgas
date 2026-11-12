<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('emails.orderReceiptTitle') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #111111;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 12px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 15px;
            line-height: 1.5;
            color: #e5e7eb;
        }
        .order-info h2 {
            margin: 0 0 16px 0;
            font-size: 18px;
            color: #111111;
        }
        .content {
            padding: 30px 20px;
        }
        .order-info {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
        }
        .info-value {
            color: #6b7280;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .products-table th,
        .products-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .products-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #374151;
        }
        .product-name {
            font-weight: 500;
        }
        .total-row {
            background-color: #f8fafc;
            font-weight: 600;
        }
        .products-table .num {
            white-space: nowrap;
        }
        .products-table .qty {
            text-align: center;
        }
        .product-image {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border-radius: 4px;
            vertical-align: middle;
        }
        .total-label {
            text-align: center !important;
        }
        .total-amount {
            font-size: 18px;
            color: #111111;
            white-space: nowrap;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .shipping-info {
            background-color: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .shipping-info h3 {
            margin: 0 0 10px 0;
            color: #92400e;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('emails.thankYouMessage') }}</h1>
            <p>{{ __('emails.contactMessage') }}</p>
        </div>

        <div class="content">
            <div class="order-info">
                <h2>{{ __('emails.orderDetailsTitle') }}</h2>
                <div class="info-row">
                    <span class="info-label">{{ __('emails.orderNumber') }}:</span>
                    <span class="info-value">#{{ $order_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('emails.orderDate') }}:</span>
                    <span class="info-value">{{ $order_date }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('emails.customerName') }}:</span>
                    <span class="info-value">{{ trim($client_name . ' ' . $client_surname) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('emails.customerEmail') }}:</span>
                    <span class="info-value">{{ $client_email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('emails.customerPhone') }}:</span>
                    <span class="info-value">{{ $client_phone }}</span>
                </div>
            </div>

            @if($client_message)
            <div class="order-info">
                <div class="info-row">
                    <span class="info-label">{{ __('emails.customerMessage') }}:</span>
                    <span class="info-value">{{ $client_message }}</span>
                </div>
            </div>
            @endif

            @if(!empty($shipping_lines))
            <div class="shipping-info">
                <h3>{{ __('emails.shippingMethod') }}</h3>
                @foreach ($shipping_lines as $line)
                    <p style="margin: 0 0 4px 0;">{{ $line }}</p>
                @endforeach
            </div>
            @endif

            @if($shipping_message)
            <div class="shipping-info">
                <h3>{{ __('emails.shippingInstructions') }}</h3>
                <p>{{ $shipping_message }}</p>
            </div>
            @endif

            <h2>{{ __('emails.orderItems') }}</h2>
            <table class="products-table">
                <thead>
                    <tr>
                        <th colspan="2">{{ __('emails.productName') }}</th>
                        <th>{{ __('emails.unitPrice') }}</th>
                        <th class="qty" title="{{ __('emails.quantity') }}">#</th>
                        <th>{{ __('emails.totalPrice') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart_content as $item)
                        @php($imageUrl = $item['media_file']['thumbnail']['url'] ?? null)
                        <tr>
                            <td style="width: 56px; padding-right: 0;">
                                @if($imageUrl)
                                    <img class="product-image" src="{{ url($imageUrl) }}" alt="" width="56" height="56">
                                @endif
                            </td>
                            <td class="product-name">
                                {{ $item['name'][app()->getLocale()] ?? $item['name']['ua'] ?? $item['name']['en'] ?? 'Product' }}
                                @if(!empty($item['sku']))
                                    <strong>({{ $item['sku'] }})</strong>
                                @endif
                            </td>
                            <td class="num">{{ number_format($item['price'], 2) }} {{ $currency }}</td>
                            <td class="num qty">{{ $item['quantity'] }}</td>
                            <td class="num">{{ number_format($item['price'] * $item['quantity'], 2) }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" class="total-label">{{ __('emails.orderTotal') }}:</td>
                        <td class="total-amount">{{ number_format($total_price, 2) }} {{ $currency }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="footer">
            <p>{{ __('emails.companyName') }}<br>
            {{ __('emails.companyContact') }}</p>
        </div>
    </div>
</body>

</html>
