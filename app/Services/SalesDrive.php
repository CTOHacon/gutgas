<?php

namespace App\Services;

use App\Exceptions\SalesDriveException;
use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Pushes site orders to the SalesDrive CRM.
 * The CRM does not dedupe by externalId, so idempotency is ours (orders.crm_order_id).
 * A CRM outage must never break the caller: push() logs and swallows failures.
 */
class SalesDrive
{
    public function isConfigured(): bool
    {
        return filled(config('services.salesdrive.url')) && filled(config('services.salesdrive.key'));
    }

    /**
     * Push the order unless it was already sent. Never throws.
     *
     * @return bool true when the order is in the CRM after the call
     */
    public function push(Order $order): bool
    {
        if (!$this->isConfigured()) {
            Log::info('SalesDrive push skipped: not configured', ['order' => $order->id]);
            return false;
        }

        $order->refresh();

        if ($order->crm_order_id) {
            return true;
        }

        try {
            $crmId = $this->createOrder($order);
        } catch (Throwable $e) {
            Log::error('SalesDrive push failed', ['order' => $order->id, 'error' => $e->getMessage()]);
            $order->forceFill(['crm_error' => mb_substr($e->getMessage(), 0, 500)])->save();
            return false;
        }

        $order->forceFill([
            'crm_order_id' => $crmId,
            'crm_sent_at'  => now(),
            'crm_error'    => null,
        ])->save();

        return true;
    }

    /**
     * Create the order in the CRM.
     *
     * @return int CRM order id
     * @throws SalesDriveException
     */
    public function createOrder(Order $order): int
    {
        if (!$this->isConfigured()) {
            throw new SalesDriveException('SalesDrive is not configured');
        }

        $url = rtrim((string) config('services.salesdrive.url'), '/') . '/handler/';

        try {
            $response = Http::timeout(10)
                ->withHeaders(['X-Api-Key' => config('services.salesdrive.key')])
                ->acceptJson()
                ->asJson()
                ->post($url, $this->orderPayload($order));
        } catch (ConnectionException $e) {
            throw new SalesDriveException('SalesDrive is unreachable');
        }

        if ($response->failed() || $response->json('success') !== true) {
            $detail = $response->json('error') ?? $response->json('message');
            throw new SalesDriveException(
                'SalesDrive error (HTTP ' . $response->status() . ')'
                . (is_string($detail) && $detail !== '' ? ': ' . $detail : '')
            );
        }

        $crmId = $response->json('data.orderId');
        if (!is_numeric($crmId)) {
            throw new SalesDriveException('SalesDrive response has no orderId');
        }

        return (int) $crmId;
    }

    /**
     * @return array<string, mixed>
     */
    public function orderPayload(Order $order): array
    {
        $payload = [
            'getResultData' => 1,
            'fName'         => $order->client_name,
            'lName'         => $order->client_surname,
            'phone'         => self::normalizePhone((string) $order->client_phone),
            'email'         => $order->client_email,
            'externalId'    => (string) $order->id,
            'sajt'          => config('services.salesdrive.site_option'),
        ];

        if (filled($order->client_message)) {
            $payload['comment'] = $order->client_message;
        }

        switch ($order->shipping_method) {
            case Order::SHIPPING_METHOD_DEPARTMENT:
            case Order::SHIPPING_METHOD_POSTAL_MACHINE:
                $payload['shipping_method'] = 'novaposhta';
                $payload['novaposhta'] = [
                    'ServiceType'     => 'Warehouse',
                    'payer'           => 'recipient',
                    'city'            => $order->shipping_city_ref,
                    'WarehouseNumber' => $order->shipping_warehouse_ref,
                ];
                break;

            case Order::SHIPPING_METHOD_COURIER:
                $payload['shipping_method'] = 'novaposhta';
                $payload['novaposhta'] = [
                    'ServiceType' => 'Doors',
                    'payer'       => 'recipient',
                    'city'        => $order->shipping_city_ref,
                ];
                $payload['shipping_address'] = trim($order->shipping_city . ', ' . $order->shipping_address, ', ');
                break;

            case Order::SHIPPING_METHOD_OTHER:
                if (filled($order->shipping_message)) {
                    $payload['shipping_address'] = $order->shipping_message;
                }
                break;
        }

        $payload['products'] = array_map(function (array $item): array {
            $product = [];
            if (filled($item['sku'] ?? null)) {
                $product['id']  = $item['sku'];
                $product['sku'] = $item['sku'];
            }

            return $product + [
                'name'        => Order::itemName($item, 'ua'),
                'costPerItem' => $item['price'],
                'amount'      => $item['quantity'],
            ];
        }, array_values($order->cart_content ?? []));

        return $payload;
    }

    /**
     * Normalize to 380XXXXXXXXX; return the raw string when impossible.
     */
    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (preg_match('/^380\d{9}$/', $digits)) {
            return $digits;
        }
        if (preg_match('/^0\d{9}$/', $digits)) {
            return '38' . $digits;
        }
        if (preg_match('/^\d{9}$/', $digits)) {
            return '380' . $digits;
        }

        return $phone;
    }
}
