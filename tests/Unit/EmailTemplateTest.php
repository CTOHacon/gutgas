<?php

namespace Tests\Unit;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Same variables EmailService passes to the customer receipt view.
     */
    private function receiptData(Order $order): array
    {
        return [
            'order'            => $order,
            'order_id'         => $order->id,
            'client_name'      => $order->client_name,
            'client_surname'   => $order->client_surname,
            'client_email'     => $order->client_email,
            'client_phone'     => $order->client_phone,
            'client_message'   => $order->client_message,
            'cart_content'     => $order->cart_content,
            'shipping_message' => $order->shipping_message,
            'shipping_method'  => $order->shipping_method,
            'shipping_lines'   => $order->shippingLines('ua'),
            'total_price'      => $order->total(),
            'order_date'       => $order->created_at->format('d/m/Y H:i'),
            'currency'         => '₴',
        ];
    }

    public function test_order_receipt_email_template_renders_correctly()
    {
        $order = Order::factory()->create([
            'client_name'      => 'Test',
            'client_surname'   => 'Customer',
            'client_email'     => 'test@example.com',
            'client_phone'     => '+38 (067) 123-45-67',
            'client_message'   => 'Please call before delivery',
            'shipping_message' => 'Leave at the door',
            'cart_content'     => [
                ['id' => 1, 'name' => ['ua' => 'Premium Widget'], 'sku' => 'PW-1', 'price' => 29.99, 'quantity' => 2],
                ['id' => 2, 'name' => ['ua' => 'Basic Gadget'], 'sku' => 'BG-1', 'price' => 15.50, 'quantity' => 1],
            ],
        ]);

        $rendered = View::make('emails.orderReceipt', $this->receiptData($order))->render();

        $this->assertStringContainsString('Test Customer', $rendered);
        $this->assertStringContainsString('test@example.com', $rendered);
        $this->assertStringContainsString('+38 (067) 123-45-67', $rendered);
        $this->assertStringContainsString('Please call before delivery', $rendered);
        $this->assertStringContainsString('Leave at the door', $rendered);
        $this->assertStringContainsString('Premium Widget', $rendered);
        $this->assertStringContainsString('Basic Gadget', $rendered);
        $this->assertStringContainsString('PW-1', $rendered);
        $this->assertStringContainsString('29.99 ₴', $rendered);
        $this->assertStringContainsString('15.50 ₴', $rendered);
        $this->assertStringContainsString('75.48 ₴', $rendered);
        $this->assertStringNotContainsString('emails.', $rendered);
    }

    public function test_email_template_handles_empty_cart_gracefully()
    {
        $order = Order::factory()->create([
            'client_name'  => 'Empty Cart Customer',
            'client_email' => 'empty@example.com',
            'cart_content' => [],
        ]);

        $rendered = View::make('emails.orderReceipt', $this->receiptData($order))->render();

        $this->assertStringContainsString('Empty Cart Customer', $rendered);
        $this->assertStringContainsString('0.00 ₴', $rendered);
    }

    public function test_email_template_renders_np_shipping_lines()
    {
        $order = Order::factory()->create([
            'client_name'     => 'Shipping Customer',
            'client_email'    => 'ship@example.com',
            'shipping_method' => Order::SHIPPING_METHOD_DEPARTMENT,
            'shipping_city'   => 'Київ',
            'shipping_warehouse' => 'Відділення №1',
        ]);

        $rendered = View::make('emails.orderReceipt', $this->receiptData($order))->render();

        $this->assertStringContainsString('Київ', $rendered);
        $this->assertStringContainsString('Відділення №1', $rendered);
        $this->assertStringNotContainsString('emails.shippingMethods', $rendered);
    }
}
