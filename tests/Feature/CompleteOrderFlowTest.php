<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CompleteOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private const CITY_REF = '8d5a980d-391c-11dd-90d9-001a92567626';

    protected function setUp(): void
    {
        parent::setUp();

        // No real Telegram / Nova Poshta calls
        Http::fake();
    }

    private function product(int $price = 1500, ?string $sku = null): Product
    {
        return Product::factory()->create(['price' => $price, 'sku' => $sku ?? uniqid('GAS-')]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'client_name'      => 'Іван',
            'client_surname'   => 'Петренко',
            'client_phone'     => '+38 (067) 123-45-67',
            'client_email'     => 'ivan.petrenko@example.com',
            'client_message'   => 'Будь ласка, доставте після 18:00',
            'cart_content'     => [['id' => $this->product()->id, 'quantity' => 2]],
            'shipping_method'  => 'other',
            'shipping_message' => 'Київ, самовивіз',
        ], $overrides);
    }

    public function test_order_is_created_and_emails_are_sent(): void
    {
        $this->post('/order/store', $this->payload())->assertRedirect(route('thankYou'));

        $this->assertDatabaseHas('orders', [
            'client_name'      => 'Іван',
            'client_surname'   => 'Петренко',
            'client_email'     => 'ivan.petrenko@example.com',
            'shipping_method'  => 'other',
            'shipping_message' => 'Київ, самовивіз',
            'locale'           => 'ua',
            'status_name'      => Order::DEFAULT_STATUS_NAME,
            'status_color'     => Order::DEFAULT_STATUS_COLOR,
            'compleated'       => 0,
        ]);

        // MAIL_MAILER=array keeps sent messages in memory
        $messages = Mail::mailer('array')->getSymfonyTransport()->messages();
        $this->assertCount(2, $messages);

        $recipients = $messages->map(fn($m) => $m->getOriginalMessage()->getTo()[0]->getAddress())->all();
        $this->assertContains('ivan.petrenko@example.com', $recipients);
    }

    public function test_thank_you_page_exposes_order_id_for_analytics_only(): void
    {
        // Follow the whole redirect chain exactly like the browser does
        // (store -> thank-you -> locale-prefixed thank-you).
        $response = $this->withoutVite()->followingRedirects()->post('/order/store', $this->payload());
        $order = Order::latest('id')->firstOrFail();

        // The order id is shared for the GTM checkout_order_submitted event,
        // but never as the `order` prop (ThankYou.vue would render it).
        $response->assertInertia(fn ($page) => $page
            ->component('ThankYou')
            ->where('analyticsOrderId', $order->id)
            ->missing('order'));

        // Flash is one-shot: reloading the thank-you page carries no id.
        $this->withoutVite()->followingRedirects()->get(route('thankYou'))
            ->assertInertia(fn ($page) => $page->where('analyticsOrderId', null));
    }

    public function test_client_supplied_price_and_name_are_ignored(): void
    {
        $product = $this->product(1500, 'REAL-SKU');

        $this->post('/order/store', $this->payload([
            'cart_content' => [[
                'id'       => $product->id,
                'quantity' => 3,
                'price'    => 1,
                'sku'      => 'FAKE',
                'name'     => ['ua' => 'Підробка'],
            ]],
        ]))->assertRedirect(route('thankYou'));

        $order = Order::firstOrFail();
        $item  = $order->cart_content[0];

        $this->assertSame(1500, $item['price']);
        $this->assertSame('REAL-SKU', $item['sku']);
        $this->assertSame($product->getTranslations('name'), $item['name']);
        $this->assertSame(3, $item['quantity']);
        $this->assertEquals(4500, $order->total());
    }

    public function test_duplicate_products_are_merged(): void
    {
        $product = $this->product();

        $this->post('/order/store', $this->payload([
            'cart_content' => [
                ['id' => $product->id, 'quantity' => 1],
                ['id' => $product->id, 'quantity' => 2],
            ],
        ]))->assertRedirect(route('thankYou'));

        $cart = Order::firstOrFail()->cart_content;
        $this->assertCount(1, $cart);
        $this->assertSame(3, $cart[0]['quantity']);
    }

    public function test_unknown_product_is_rejected(): void
    {
        $this->post('/order/store', $this->payload([
            'cart_content' => [['id' => 99999, 'quantity' => 1]],
        ]))->assertSessionHasErrors('cart_content.0.id');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_empty_cart_and_bad_quantity_are_rejected(): void
    {
        $product = $this->product();

        $this->post('/order/store', $this->payload(['cart_content' => []]))
            ->assertSessionHasErrors('cart_content');
        $this->post('/order/store', $this->payload([
            'cart_content' => [['id' => $product->id, 'quantity' => 0]],
        ]))->assertSessionHasErrors('cart_content.0.quantity');
    }

    public function test_status_fields_from_client_are_ignored(): void
    {
        $this->post('/order/store', $this->payload([
            'status_name'  => 'Отримано',
            'status_color' => 'success',
            'compleated'   => true,
        ]))->assertRedirect(route('thankYou'));

        $order = Order::firstOrFail();
        $this->assertSame(Order::DEFAULT_STATUS_NAME, $order->status_name);
        $this->assertSame(Order::DEFAULT_STATUS_COLOR, $order->status_color);
        $this->assertEquals(0, $order->compleated);
    }

    public function test_phone_and_email_are_validated(): void
    {
        $this->post('/order/store', $this->payload(['client_phone' => '12345']))
            ->assertSessionHasErrors('client_phone');
        $this->post('/order/store', $this->payload(['client_email' => 'not-an-email']))
            ->assertSessionHasErrors('client_email');
        $this->post('/order/store', $this->payload(['client_surname' => '']))
            ->assertSessionHasErrors('client_surname');
    }

    public static function intlPhoneProvider(): array
    {
        return [
            'georgia'    => ['+995 555 12 34 56', 'ka'],
            'moldova'    => ['+373 (69) 123-456', 'ro'],
            'kazakhstan' => ['+7 701 123-45-67', 'kk'],
            'eu'         => ['+49 30 1234567', 'en'],
        ];
    }

    /** @dataProvider intlPhoneProvider */
    public function test_international_phone_is_accepted_on_every_locale(string $phone, string $locale): void
    {
        $this->post('/order/store', $this->payload(['client_phone' => $phone, 'locale' => $locale]))
            ->assertSessionDoesntHaveErrors('client_phone');
        $this->assertDatabaseHas('orders', ['client_phone' => $phone, 'locale' => $locale]);
    }

    /** @dataProvider intlPhoneProvider */
    public function test_international_phone_is_accepted_on_ua_and_ru(string $phone): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        foreach (['ua', 'ru'] as $locale) {
            $this->post('/order/store', $this->payload(['client_phone' => $phone, 'locale' => $locale]))
                ->assertSessionDoesntHaveErrors('client_phone');
        }
    }

    public function test_country_picker_format_is_accepted(): void
    {
        $this->post('/order/store', $this->payload(['client_phone' => '+380 67 123 45 67']))
            ->assertSessionDoesntHaveErrors('client_phone');
        $this->assertDatabaseHas('orders', ['client_phone' => '+380 67 123 45 67']);
    }

    public function test_ukrainian_phone_is_accepted_on_every_locale(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        foreach (array_keys(config('app.locales')) as $locale) {
            $this->post('/order/store', $this->payload(['locale' => $locale]))
                ->assertSessionDoesntHaveErrors('client_phone');
        }
    }

    public function test_garbage_phone_is_rejected_on_every_locale(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        $base = $this->payload();
        foreach (array_keys(config('app.locales')) as $locale) {
            foreach (['12345', 'abc', '+0123456789012', '+99 5', '995555123456', '+995 555 12 34 56 78 90 12 34'] as $phone) {
                $this->post('/order/store', array_merge($base, ['client_phone' => $phone, 'locale' => $locale]))
                    ->assertSessionHasErrors('client_phone');
            }
        }
    }

    public function test_phone_error_uses_generic_message(): void
    {
        $this->post('/order/store', $this->payload(['client_phone' => '12345', 'locale' => 'en']))
            ->assertSessionHasErrors(['client_phone' => __('validation.phone', ['attribute' => 'phone'], 'en')]);
    }

    public function test_rejected_phone_is_logged(): void
    {
        Log::spy();

        $this->post('/order/store', $this->payload(['client_phone' => '12345']))
            ->assertSessionHasErrors(['client_phone' => __('validation.phone', ['attribute' => 'телефон'], 'ua')]);

        $this->assertDatabaseCount('orders', 0);
        Log::shouldHaveReceived('info')
            ->with('Order rejected by validation', ['fields' => ['client_phone']])
            ->once();
    }

    public function test_np_department_requires_city_and_warehouse(): void
    {
        $this->post('/order/store', $this->payload([
            'shipping_method'  => 'np_department',
            'shipping_message' => null,
        ]))->assertSessionHasErrors([
            'shipping_city', 'shipping_city_ref', 'shipping_warehouse', 'shipping_warehouse_ref',
        ])->assertSessionDoesntHaveErrors('shipping_message');
    }

    public function test_np_postal_machine_is_stored_with_warehouse(): void
    {
        $this->post('/order/store', $this->payload([
            'shipping_method'        => 'np_postal_machine',
            'shipping_city'          => 'Київ',
            'shipping_city_ref'      => self::CITY_REF,
            'shipping_area'          => 'Київська область',
            'shipping_warehouse'     => 'Поштомат №1',
            'shipping_warehouse_ref' => 'wh-ref',
            'shipping_message'       => 'ignored',
        ]))->assertRedirect(route('thankYou'));

        $this->assertDatabaseHas('orders', [
            'shipping_method'        => 'np_postal_machine',
            'shipping_city'          => 'Київ',
            'shipping_city_ref'      => self::CITY_REF,
            'shipping_warehouse'     => 'Поштомат №1',
            'shipping_warehouse_ref' => 'wh-ref',
            'shipping_address'       => null,
            'shipping_message'       => null,
        ]);
    }

    public function test_np_courier_requires_address_but_not_warehouse(): void
    {
        $base = $this->payload([
            'shipping_method'   => 'np_courier',
            'shipping_city'     => 'Львів',
            'shipping_city_ref' => self::CITY_REF,
            'shipping_message'  => null,
        ]);

        $this->post('/order/store', $base)
            ->assertSessionHasErrors('shipping_address')
            ->assertSessionDoesntHaveErrors(['shipping_warehouse', 'shipping_warehouse_ref']);

        $this->post('/order/store', $base + ['shipping_address' => 'вул. Шевченка 1, кв. 2'])
            ->assertRedirect(route('thankYou'));

        $this->assertDatabaseHas('orders', [
            'shipping_method'  => 'np_courier',
            'shipping_address' => 'вул. Шевченка 1, кв. 2',
            'shipping_warehouse' => null,
        ]);
    }

    public function test_other_method_requires_shipping_message(): void
    {
        $this->post('/order/store', $this->payload(['shipping_message' => '']))
            ->assertSessionHasErrors('shipping_message');
    }

    public function test_unknown_shipping_method_is_rejected(): void
    {
        $this->post('/order/store', $this->payload(['shipping_method' => 'teleport']))
            ->assertSessionHasErrors('shipping_method');
    }

    public function test_order_store_is_rate_limited(): void
    {
        $responses = [];
        for ($i = 0; $i < 11; $i++) {
            $responses[] = $this->post('/order/store', ['client_name' => ''])->status();
        }

        $this->assertSame(429, end($responses));
    }

    public function test_expired_csrf_token_returns_an_error_instead_of_a_silent_redirect(): void
    {
        // CSRF checks are skipped in unit tests by default; force them on
        $this->app->bind(VerifyCsrfToken::class, fn ($app) => new class($app, $app['encrypter']) extends VerifyCsrfToken {
            protected function runningUnitTests()
            {
                return false;
            }
        });

        $this->from('/ua/checkout')
            ->post('/order/store', $this->payload())
            ->assertRedirect('/ua/checkout')
            ->assertSessionHasErrors('page_expired');

        $this->assertDatabaseCount('orders', 0);
    }
}
