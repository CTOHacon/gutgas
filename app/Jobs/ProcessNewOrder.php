<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\SalesDrive;
use App\Services\TelegramNotifier;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Pushes a new order to the CRM, then posts it to Telegram with the CRM order number and link.
 * Dispatched ->afterResponse() so the customer never waits on CRM / Telegram latency.
 * Neither step throws: a CRM failure is reported in the Telegram message instead.
 */
class ProcessNewOrder
{
    use Dispatchable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function handle(SalesDrive $salesDrive, TelegramNotifier $telegram): void
    {
        $salesDrive->push($this->order);
        $this->order->refresh();

        $telegram->send($this->message($salesDrive));
    }

    private function message(SalesDrive $salesDrive): string
    {
        $order = $this->order;

        $products = [];
        foreach (array_values($order->cart_content) as $index => $product) {
            $label = ($index + 1) . '. ' . Order::itemName($product, 'ua');
            $products[$label] = "{$product['quantity']} шт. × " . number_format($product['price'], 0, '', ' ') . ' грн';
        }

        if ($order->crm_order_id) {
            $crm = ['Заявка' => "№{$order->crm_order_id}"];
        } elseif ($salesDrive->isConfigured()) {
            $crm = ['⚠️ Не відправлено' => $order->crm_error ?: 'невідома помилка'];
        } else {
            $crm = [];
        }

        $links = ['Відкрити в адмінці' => route('admin.order.edit', $order->id)];
        if ($order->crm_order_id) {
            $links["Відкрити в CRM (№{$order->crm_order_id})"] = $salesDrive->orderUrl($order->crm_order_id);
        }

        return TelegramNotifier::format(
            "🛒 Нове замовлення №{$order->id}",
            [
                '👤 Клієнт' => [
                    "Ім'я"     => trim($order->client_name . ' ' . $order->client_surname),
                    'Телефон'  => $order->client_phone,
                    'Email'    => $order->client_email,
                    'Коментар' => $order->client_message,
                ],
                '🚚 Доставка' => [
                    'Спосіб'     => $order->shipping_method === Order::SHIPPING_METHOD_OTHER
                        ? 'Інше'
                        : ($order->shipping_method ? __('emails.shippingMethods.' . $order->shipping_method, [], 'ua') : null),
                    'Місто'      => $order->shipping_city
                        ? trim($order->shipping_city . ($order->shipping_area ? " ({$order->shipping_area})" : ''))
                        : null,
                    'Відділення' => $order->shipping_warehouse,
                    'Адреса'     => $order->shipping_address,
                    'Деталі'     => $order->shipping_message,
                ],
                '📦 Товари' => $products,
                '💰 Разом'  => [
                    'Сума' => number_format($order->total(), 0, '', ' ') . ' грн',
                ],
                '🗂 CRM' => $crm,
            ],
            $links
        );
    }
}
