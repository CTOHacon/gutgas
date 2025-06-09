<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_receipt_email_template_renders_correctly()
    {
        // Arrange
        $order = Order::factory()->create([
            'name'           => 'Test Customer',
            'customer_email' => 'test@example.com',
            'phone'          => '+1234567890',
            'address'        => '123 Test Street, Test City, 12345',
            'cart'           => json_encode([
                [
                    'id'       => 1,
                    'name'     => 'Premium Widget',
                    'price'    => 29.99,
                    'quantity' => 2,
                    'image'    => 'widget.jpg'
                ],
                [
                    'id'       => 2,
                    'name'     => 'Basic Gadget',
                    'price'    => 15.50,
                    'quantity' => 1,
                    'image'    => 'gadget.jpg'
                ]
            ]),
            'sum'            => 75.48,
            'payment_type'   => 'card'
        ]);

        // Act
        $renderedTemplate = View::make('emails.orderReceipt', ['order' => $order])->render();

        // Assert
        $this->assertStringContainsString('Test Customer', $renderedTemplate);
        $this->assertStringContainsString('test@example.com', $renderedTemplate);
        $this->assertStringContainsString('+1234567890', $renderedTemplate);
        $this->assertStringContainsString('123 Test Street, Test City, 12345', $renderedTemplate);
        $this->assertStringContainsString('Premium Widget', $renderedTemplate);
        $this->assertStringContainsString('Basic Gadget', $renderedTemplate);
        $this->assertStringContainsString('$29.99', $renderedTemplate);
        $this->assertStringContainsString('$15.50', $renderedTemplate);
        $this->assertStringContainsString('$75.48', $renderedTemplate);
        $this->assertStringContainsString('card', $renderedTemplate);
        $this->assertStringContainsString('Order Confirmation', $renderedTemplate);
    }

    public function test_email_template_handles_empty_cart_gracefully()
    {
        // Arrange
        $order = Order::factory()->create([
            'name'           => 'Empty Cart Customer',
            'customer_email' => 'empty@example.com',
            'cart'           => json_encode([]),
            'sum'            => 0
        ]);

        // Act & Assert - Should not throw exception
        $renderedTemplate = View::make('emails.orderReceipt', ['order' => $order])->render();
        $this->assertStringContainsString('Empty Cart Customer', $renderedTemplate);
        $this->assertStringContainsString('$0.00', $renderedTemplate);
    }

    public function test_email_template_handles_malformed_cart_data()
    {
        // Arrange
        $order = Order::factory()->create([
            'name'           => 'Malformed Cart Customer',
            'customer_email' => 'malformed@example.com',
            'cart'           => 'invalid json',
            'sum'            => 25.00
        ]);

        // Act & Assert - Should not throw exception
        $renderedTemplate = View::make('emails.orderReceipt', ['order' => $order])->render();
        $this->assertStringContainsString('Malformed Cart Customer', $renderedTemplate);
        $this->assertStringContainsString('$25.00', $renderedTemplate);
    }

}
