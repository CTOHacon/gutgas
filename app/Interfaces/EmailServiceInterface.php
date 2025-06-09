<?php

namespace App\Interfaces;

use App\Models\Order;

interface EmailServiceInterface
{
    /**
     * Send order confirmation email to customer
     *
     * @param Order $order
     * @return bool
     */
    public function sendOrderConfirmation(Order $order): bool;

    /**
     * Send order notification email to admin
     *
     * @param Order $order
     * @return bool
     */
    public function sendOrderNotification(Order $order): bool;

}
