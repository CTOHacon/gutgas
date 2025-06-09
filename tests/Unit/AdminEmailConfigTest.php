<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminEmailConfigTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uses_admin_email_environment_variable()
    {
        Mail::fake();

        // Create a test order
        $order = Order::factory()->create([
            'client_name'  => 'Test Customer',
            'client_email' => 'customer@example.com',
            'client_phone' => '+38 (123) 456-78-90',
            'cart_content' => [
                [
                    'id'       => 1,
                    'name'     => ['ua' => 'Test Product'],
                    'price'    => 100,
                    'quantity' => 1
                ]
            ]
        ]);

        // Test with default environment value
        $emailService = new EmailService();
        $result       = $emailService->sendOrderNotification($order);

        $this->assertTrue($result);

        // Verify that email was sent to the admin email from environment
        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            $adminEmail = env('ADMIN_EMAIL', 'sale@gutgas.eu');
            return $mail->hasTo($adminEmail);
        });
    }

    /** @test */
    public function it_falls_back_to_default_when_admin_email_not_set()
    {
        Mail::fake();

        // Temporarily clear the ADMIN_EMAIL env var
        $originalValue = $_ENV['ADMIN_EMAIL'] ?? null;
        unset($_ENV['ADMIN_EMAIL']);

        $order = Order::factory()->create([
            'client_name'  => 'Test Customer',
            'client_email' => 'customer@example.com',
            'cart_content' => [
                [
                    'id'       => 1,
                    'name'     => ['ua' => 'Test Product'],
                    'price'    => 100,
                    'quantity' => 1
                ]
            ]
        ]);

        $emailService = new EmailService();
        $result       = $emailService->sendOrderNotification($order);

        $this->assertTrue($result);

        // Should fall back to default
        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            return $mail->hasTo('sale@gutgas.eu');
        });

        // Restore original value
        if ($originalValue !== null) {
            $_ENV['ADMIN_EMAIL'] = $originalValue;
        }
    }

    /** @test */
    public function mail_controller_uses_admin_email_variable()
    {
        Mail::fake();

        // Test consultation email
        $response = $this->post('/consultation', [
            'name'     => 'John Doe',
            'phone'    => '+38 (067) 123-45-67',
            'email'    => 'john@example.com',
            'question' => 'Test question'
        ]);

        $response->assertStatus(302); // Redirect to thank you page

        // Verify email was sent to admin
        Mail::assertSent(\Illuminate\Mail\Mailable::class, function ($mail) {
            $adminEmail = env('ADMIN_EMAIL', 'sale@gutgas.eu');
            return $mail->hasTo($adminEmail);
        });
    }

}
