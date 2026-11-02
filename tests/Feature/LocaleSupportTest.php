<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocaleSupportTest extends TestCase
{
    use RefreshDatabase;

    public function test_georgian_registration_page_renders_with_proper_lang(): void
    {
        $this->assertArrayHasKey('ka', config('app.locales'));

        $this->withoutVite()->get('/ka/registration')
            ->assertOk()
            ->assertSee('lang="ka-GE"', false);
    }

    public function test_order_store_accepts_georgian_locale(): void
    {
        Http::fake();
        $product = Product::factory()->create(['price' => 1500, 'sku' => uniqid('GAS-')]);

        $this->post('/order/store', [
            'client_name'      => 'Ivan',
            'client_surname'   => 'Petrenko',
            'client_phone'     => '+38 (067) 123-45-67',
            'client_email'     => 'ivan.petrenko@example.com',
            'cart_content'     => [['id' => $product->id, 'quantity' => 1]],
            'shipping_method'  => 'other',
            'shipping_message' => 'Tbilisi',
            'locale'           => 'ka',
        ])->assertRedirect(route('thankYou'));

        $this->assertDatabaseHas('orders', ['client_email' => 'ivan.petrenko@example.com', 'locale' => 'ka']);
    }

    public static function newLocales(): array
    {
        return [
            'en' => ['en', 'en-US'],
            'ro' => ['ro', 'ro-MD'],
            'kk' => ['kk', 'kk-KZ'],
        ];
    }

    /** @dataProvider newLocales */
    public function test_new_locale_registration_page_renders_with_proper_lang(string $code, string $full): void
    {
        $this->assertSame($full, config('app.locales')[$code]);

        $this->withoutVite()->get("/{$code}/registration")
            ->assertOk()
            ->assertSee('lang="' . $full . '"', false);
    }

    /** @dataProvider newLocales */
    public function test_new_locale_is_not_redirected_to_ua(string $code): void
    {
        $response = $this->withoutVite()->get("/{$code}/registration");
        $this->assertNotSame(301, $response->getStatusCode());
        $response->assertOk();
    }

    /** @dataProvider newLocales */
    public function test_order_store_accepts_new_locales(string $code): void
    {
        Http::fake();
        $product = Product::factory()->create(['price' => 1500, 'sku' => uniqid('GAS-')]);
        $email = "order.{$code}@example.com";

        $this->post('/order/store', [
            'client_name'      => 'Ivan',
            'client_surname'   => 'Petrenko',
            'client_phone'     => '+38 (067) 123-45-67',
            'client_email'     => $email,
            'cart_content'     => [['id' => $product->id, 'quantity' => 1]],
            'shipping_method'  => 'other',
            'shipping_message' => 'Somewhere',
            'locale'           => $code,
        ])->assertRedirect(route('thankYou'));

        $this->assertDatabaseHas('orders', ['client_email' => $email, 'locale' => $code]);
    }
}
