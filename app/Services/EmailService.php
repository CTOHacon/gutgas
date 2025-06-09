<?php

namespace App\Services;

use App\Interfaces\EmailServiceInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService implements EmailServiceInterface
{
    /**
     * Send order confirmation email to customer
     *
     * @param Order $order
     * @return bool
     */
    public function sendOrderConfirmation(Order $order): bool
    {
        try {
            $emailData = $this->prepareOrderConfirmationData($order);

            Mail::send('emails.orderReceipt', $emailData, function ($message) use ($order) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($order->client_email, $order->client_name);
                $message->subject(__('emails.orderConfirmationSubject', ['order_id' => $order->id]));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id'       => $order->id,
                'customer_email' => $order->client_email,
                'error'          => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send order notification email to admin
     *
     * @param Order $order
     * @return bool
     */
    public function sendOrderNotification(Order $order): bool
    {
        try {
            $emailData = $this->prepareOrderNotificationData($order);

            Mail::send('emails.newOrder', $emailData, function ($message) use ($order) {
                $message->from('form-manager@gutgas.eu', 'Gutgas Sale manager');
                $message->to('sale@gutgas.eu');
                $message->subject('$$$ Нове Замовлення #' . $order->id . ' $$$');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order notification email', [
                'order_id' => $order->id,
                'error'    => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Prepare data for customer order confirmation email
     *
     * @param Order $order
     * @return array
     */
    private function prepareOrderConfirmationData(Order $order): array
    {
        $totalPrice = $this->calculateOrderTotal($order);

        return [
            'order'            => $order,
            'order_id'         => $order->id,
            'client_name'      => $order->client_name,
            'client_email'     => $order->client_email,
            'client_phone'     => $order->client_phone,
            'client_message'   => $order->client_message,
            'cart_content'     => $order->cart_content,
            'shipping_message' => $order->shipping_message,
            'total_price'      => $totalPrice,
            'order_date'       => $order->created_at->format('d/m/Y H:i'),
            'currency'         => '₴'
        ];
    }

    /**
     * Prepare data for admin order notification email
     *
     * @param Order $order
     * @return array
     */
    private function prepareOrderNotificationData(Order $order): array
    {
        return [
            'order_id'         => $order->id,
            'client_name'      => $order->client_name,
            'client_email'     => $order->client_email,
            'client_phone'     => $order->client_phone,
            'client_message'   => $order->client_message,
            'cart_content'     => $order->cart_content,
            'shipping_message' => $order->shipping_message,
        ];
    }

    /**
     * Calculate total price for the order
     *
     * @param Order $order
     * @return float
     */
    private function calculateOrderTotal(Order $order): float
    {
        $total = 0;
        foreach ($order->cart_content as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }

}
