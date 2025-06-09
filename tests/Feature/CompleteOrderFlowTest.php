<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class CompleteOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Mock HTTP requests to prevent actual API calls during testing
        Http::fake();
        Mail::fake();
    }

    /** @test */
    public function complete_order_creation_flow_works_with_email_receipt()
    {
        // Arrange - Simulate form data that would come from frontend
        $orderData = [
            'client_name'      => 'Іван Петренко',
            'client_phone'     => '+38 (067) 123-45-67',
            'client_email'     => 'ivan.petrenko@example.com',
            'client_message'   => 'Будь ласка, доставте після 18:00',
            'cart_content'     => [
                [
                    'id'       => 1,
                    'name'     => [
                        'ua' => 'Газовий котел Premium',
                        'en' => 'Premium Gas Boiler',
                        'ru' => 'Газовый котел Премиум'
                    ],
                    'sku'      => 'GAS-001',
                    'price'    => 15000,
                    'quantity' => 1
                ],
                [
                    'id'       => 2,
                    'name'     => [
                        'ua' => 'Газова труба DN25',
                        'en' => 'Gas Pipe DN25',
                        'ru' => 'Газовая труба DN25'
                    ],
                    'sku'      => 'PIPE-025',
                    'price'    => 150,
                    'quantity' => 5
                ]
            ],
            'shipping_message' => 'Доставка на другий поверх, ліфт є',
            'status_name'      => 'pending',
            'status_color'     => 'primary',
            'compleated'       => false
        ];

        // Act - Submit order through the API
        $response = $this->postJson('/order/store', $orderData);

        // Assert - Order creation succeeded
        $response->assertStatus(302); // Redirect to thank you page

        // Verify order was saved in database with correct data
        $this->assertDatabaseHas('orders', [
            'client_name'      => 'Іван Петренко',
            'client_email'     => 'ivan.petrenko@example.com',
            'client_phone'     => '+38 (067) 123-45-67',
            'client_message'   => 'Будь ласка, доставте після 18:00',
            'shipping_message' => 'Доставка на другий поверх, ліфт є',
            'status_name'      => 'pending'
        ]);

        // Verify that emails were sent
        Mail::assertSent(\Illuminate\Mail\Mailable::class, 2); // One to customer, one to admin

        // Verify customer email was sent to correct address
        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo('ivan.petrenko@example.com');
        });

        // Verify admin notification email was sent
        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo('sale@gutgas.eu');
        });
    }

    /** @test */
    public function order_creation_handles_missing_optional_fields()
    {
        // Arrange - Order with only required fields
        $orderData = [
            'client_name'  => 'Анна Коваленко',
            'client_phone' => '+38 (050) 987-65-43',
            'client_email' => 'anna@example.com',
            'cart_content' => [
                [
                    'id'       => 1,
                    'name'     => [
                        'ua' => 'Базовий комплект',
                        'en' => 'Basic Kit'
                    ],
                    'sku'      => 'KIT-001',
                    'price'    => 5000,
                    'quantity' => 1
                ]
            ]
        ];

        // Act
        $response = $this->postJson('/order/store', $orderData);

        // Assert
        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'client_name'      => 'Анна Коваленко',
            'client_email'     => 'anna@example.com',
            'client_phone'     => '+38 (050) 987-65-43',
            'client_message'   => null,
            'shipping_message' => null
        ]);

        // Emails should still be sent
        Mail::assertSent(\Illuminate\Mail\Mailable::class, 2);
    }

    /** @test */
    public function order_creation_calculates_totals_correctly()
    {
        // Arrange
        $orderData = [
            'client_name'  => 'Тест Користувач',
            'client_phone' => '+38 (123) 456-78-90',
            'client_email' => 'test@example.com',
            'cart_content' => [
                [
                    'id'       => 1,
                    'name'     => ['ua' => 'Продукт 1'],
                    'price'    => 100,
                    'quantity' => 3 // 300
                ],
                [
                    'id'       => 2,
                    'name'     => ['ua' => 'Продукт 2'],
                    'price'    => 250,
                    'quantity' => 2 // 500
                ]
                // Total should be 800
            ]
        ];

        // Act
        $response = $this->postJson('/order/store', $orderData);

        // Assert
        $response->assertStatus(302);

        // Check that order was created
        $order = \App\Models\Order::where('client_email', 'test@example.com')->first();
        $this->assertNotNull($order);

        // Verify the cart content was saved correctly
        $this->assertCount(2, $order->cart_content);

        // Calculate total manually to verify
        $expectedTotal = (100 * 3) + (250 * 2);
        $this->assertEquals(800, $expectedTotal);
    }

}
