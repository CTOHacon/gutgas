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
}
