<?php

namespace Tests\Unit;

use App\Interfaces\EmailServiceInterface;
use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmailServiceInterface $emailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = new EmailService();
    }

    /** @test */
    public function it_can_send_order_confirmation_email()
    {
        Mail::fake();

        $order = Order::factory()->create([
            'client_name'  => 'John Doe',
            'client_email' => 'john@example.com',
            'client_phone' => '+38 (123) 456-78-90',
            'cart_content' => [
                [
                    'id'       => 1,
                    'name'     => [
                        'ua' => 'Test Product',
                        'en' => 'Test Product'
                    ],
                    'sku'      => 'TEST-001',
                    'price'    => 100,
                    'quantity' => 2
                ]
            ]
        ]);

        $result = $this->emailService->sendOrderConfirmation($order);

        $this->assertTrue($result);
        Mail::assertSent(\Illuminate\Mail\Mailable::class);
    }

    /** @test */
    public function it_can_send_order_notification_email()
    {
        Mail::fake();

        $order = Order::factory()->create([
            'client_name'  => 'John Doe',
            'client_email' => 'john@example.com',
            'client_phone' => '+38 (123) 456-78-90',
            'cart_content' => [
                [
                    'id'       => 1,
                    'name'     => [
                        'ua' => 'Test Product',
                        'en' => 'Test Product'
                    ],
                    'sku'      => 'TEST-001',
                    'price'    => 100,
                    'quantity' => 2
                ]
            ]
        ]);

        $result = $this->emailService->sendOrderNotification($order);

        $this->assertTrue($result);
        Mail::assertSent(\Illuminate\Mail\Mailable::class);
    }

}
