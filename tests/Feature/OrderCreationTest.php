<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Interfaces\EmailServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Mockery;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Mock HTTP requests to prevent actual API calls during testing
        Http::fake();
    }

    public function test_order_creation_sends_customer_receipt_email()
    {
        // Arrange
        Mail::fake();

        $orderData = [
            'name'           => 'John Doe',
            'phone'          => '+1234567890',
            'customer_email' => 'john@example.com',
            'address'        => '123 Main St, City, 12345',
            'cart'           => json_encode([
                [
                    'id'       => 1,
                    'name'     => 'Test Product',
                    'price'    => 29.99,
                    'quantity' => 2,
                    'image'    => 'test-product.jpg'
                ]
            ]),
            'sum'            => 59.98,
            'payment_type'   => 'card'
        ];

        // Act
        $response = $this->post(route('orders.store'), $orderData);

        // Assert
        $response->assertStatus(200);

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'name'           => 'John Doe',
            'customer_email' => 'john@example.com',
            'sum'            => 59.98
        ]);

        // Verify email was queued/sent
        Mail::assertQueued(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function test_order_creation_handles_email_service_injection()
    {
        // Arrange
        $mockEmailService = Mockery::mock(EmailServiceInterface::class);
        $mockEmailService->shouldReceive('sendOrderConfirmation')
            ->once()
            ->with(Mockery::type(Order::class))
            ->andReturn(true);
        $mockEmailService->shouldReceive('sendOrderNotification')
            ->once()
            ->with(Mockery::type(Order::class))
            ->andReturn(true);

        $this->app->instance(EmailServiceInterface::class, $mockEmailService);

        $orderData = [
            'name'           => 'Jane Doe',
            'phone'          => '+1234567890',
            'customer_email' => 'jane@example.com',
            'address'        => '456 Oak St, Town, 67890',
            'cart'           => json_encode([
                [
                    'id'       => 2,
                    'name'     => 'Another Product',
                    'price'    => 15.50,
                    'quantity' => 1,
                    'image'    => 'another-product.jpg'
                ]
            ]),
            'sum'            => 15.50,
            'payment_type'   => 'cash'
        ];

        // Act
        $response = $this->post(route('orders.store'), $orderData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'name'           => 'Jane Doe',
            'customer_email' => 'jane@example.com'
        ]);
    }

    public function test_order_creation_without_email_still_works()
    {
        // Test that orders can be created even without customer email
        $orderData = [
            'name'         => 'No Email Customer',
            'phone'        => '+1234567890',
            'address'      => '789 Pine St, Village, 13579',
            'cart'         => json_encode([
                [
                    'id'       => 3,
                    'name'     => 'Basic Product',
                    'price'    => 10.00,
                    'quantity' => 1,
                    'image'    => 'basic-product.jpg'
                ]
            ]),
            'sum'          => 10.00,
            'payment_type' => 'cash'
        ];

        // Act
        $response = $this->post(route('orders.store'), $orderData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'name' => 'No Email Customer',
            'sum'  => 10.00
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}
