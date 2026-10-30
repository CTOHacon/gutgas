<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminEmailConfigTest extends TestCase
{
    use RefreshDatabase;

    private const DEFAULT_ADMIN_EMAIL = 'sale@gutgas.eu';

    /**
     * Recipient addresses of every message captured by the in-memory "array" mailer.
     *
     * @return string[]
     */
    private function sentRecipients(): array
    {
        $recipients = [];
        foreach (Mail::mailer('array')->getSymfonyTransport()->messages() as $message) {
            foreach ($message->getEnvelope()->getRecipients() as $address) {
                $recipients[] = $address->getAddress();
            }
        }

        return $recipients;
    }

    private function makeOrder(): Order
    {
        return Order::factory()->create([
            'client_name'  => 'Test Customer',
            'client_email' => 'customer@example.com',
            'client_phone' => '+38 (123) 456-78-90',
            'cart_content' => [
                [
                    'id'       => 1,
                    'name'     => ['ua' => 'Test Product'],
                    'sku'      => 'TEST-001',
                    'price'    => 100,
                    'quantity' => 1,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_uses_admin_email_environment_variable()
    {
        putenv('ADMIN_EMAIL=configured-admin@example.com');
        $_ENV['ADMIN_EMAIL']    = 'configured-admin@example.com';
        $_SERVER['ADMIN_EMAIL'] = 'configured-admin@example.com';

        $result = (new EmailService())->sendOrderNotification($this->makeOrder());

        $this->assertTrue($result);
        $this->assertSame(['configured-admin@example.com'], $this->sentRecipients());
    }

    /** @test */
    public function it_falls_back_to_default_when_admin_email_not_set()
    {
        putenv('ADMIN_EMAIL');
        unset($_ENV['ADMIN_EMAIL'], $_SERVER['ADMIN_EMAIL']);

        $result = (new EmailService())->sendOrderNotification($this->makeOrder());

        $this->assertTrue($result);
        $this->assertSame([self::DEFAULT_ADMIN_EMAIL], $this->sentRecipients());
    }

    /** @test */
    public function mail_controller_uses_admin_email_variable()
    {
        putenv('ADMIN_EMAIL=configured-admin@example.com');
        $_ENV['ADMIN_EMAIL']    = 'configured-admin@example.com';
        $_SERVER['ADMIN_EMAIL'] = 'configured-admin@example.com';

        $response = $this->post('/mail/consultation', [
            'name'     => 'John Doe',
            'phone'    => '+38 (067) 123-45-67',
            'email'    => 'john@example.com',
            'question' => 'Test question',
        ]);

        $response->assertRedirect();
        $this->assertSame(['configured-admin@example.com'], $this->sentRecipients());
    }
}
