<?php

namespace Tests\Feature;

use App\Models\GlobalSettings;
use App\Models\ProductRegistration;
use App\Services\TelegramNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id'   => '12345',
        ]);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'first_name'           => 'Іван',
            'last_name'            => 'Петренко',
            'phone'                => '+38 (067) 123-45-67',
            'email'                => 'ivan@example.com',
            'city'                 => 'Київ',
            'address'              => 'вул. Хрещатик, 1',
            'model'                => 'GG-500',
            'serial_number'        => 'SN123456',
            'purchase_date'        => now()->subDays(3)->format('Y-m-d'),
            'price'                => 25000,
            'purchase_place'       => 'Магазин, Київ',
            'subscription_consent' => true,
            'locale'               => 'ua',
            'website'              => '',
        ], $overrides);
    }

    public function test_valid_submission_is_stored_and_sent_to_telegram(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $response = $this->post(route('productRegistration.store'), $this->validData([
            'first_name' => 'Іван <b>&',
        ]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_registrations', [
            'last_name'            => 'Петренко',
            'serial_number'        => 'SN123456',
            'price'                => 25000,
            'subscription_consent' => 1,
        ]);

        Http::assertSent(function (Request $request) {
            return str_starts_with($request->url(), 'https://api.telegram.org/bottest-token/sendMessage')
                && $request['chat_id'] === '12345'
                && $request['parse_mode'] === 'HTML'
                && str_contains($request['text'], '🛡 Реєстрація продукту #')
                && str_contains($request['text'], 'Іван &lt;b&gt;&amp;')
                && !str_contains($request['text'], 'Іван <b>')
                && str_contains($request['text'], 'SN123456');
        });
    }

    public function test_invalid_input_is_rejected(): void
    {
        Http::fake();

        $response = $this->post(route('productRegistration.store'), $this->validData([
            'first_name'    => '',
            'phone'         => '123',
            'email'         => 'not-an-email',
            'serial_number' => '',
            'purchase_date' => now()->addDays(2)->format('Y-m-d'),
            'price'         => -5,
        ]));

        $response->assertSessionHasErrors([
            'first_name', 'phone', 'email', 'serial_number', 'purchase_date', 'price',
        ]);
        $this->assertDatabaseCount('product_registrations', 0);
        Http::assertNothingSent();
    }

    public function test_honeypot_submission_is_not_stored(): void
    {
        Http::fake();

        $response = $this->post(route('productRegistration.store'), $this->validData(['website' => 'http://spam.example']));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 0);
        Http::assertNothingSent();
    }

    public function test_honeypot_with_invalid_data_fails_silently(): void
    {
        Http::fake();

        $response = $this->post(route('productRegistration.store'), ['website' => 'spam']);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 0);
    }

    public function test_telegram_failure_does_not_break_submission(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => false], 500)]);

        $response = $this->post(route('productRegistration.store'), $this->validData());

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 1);
    }

    public function test_telegram_connection_exception_does_not_break_submission(): void
    {
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('timeout'));

        $response = $this->post(route('productRegistration.store'), $this->validData());

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 1);
        $this->assertFalse(app(TelegramNotifier::class)->send('x'));
    }

    public function test_public_page_is_available(): void
    {
        $this->withoutVite()->get("/ua/registration")->assertOk();
    }

    public function test_menu_migration_adds_link_once(): void
    {
        GlobalSettings::set('headerMenu_ua', [['title' => 'Головна', 'url' => '/']]);

        $migration = require database_path('migrations/2025_08_20_100100_add_product_registration_permissions_and_menu.php');
        $migration->up();
        $migration->up();

        $items = json_decode(GlobalSettings::get('headerMenu_ua'), true);
        $this->assertCount(2, $items);
        $this->assertSame('/ua/product-registration', $items[1]['url']);
    }

    public function test_menu_url_migration_switches_to_registration(): void
    {
        GlobalSettings::set('headerMenu_ua', [
            ['title' => 'Головна', 'url' => '/'],
            ['title' => 'Реєстрація продукту', 'url' => '/ua/product-registration'],
        ]);

        $migration = require database_path('migrations/2026_10_30_120000_rename_product_registration_menu_url.php');
        $migration->up();

        $items = json_decode(GlobalSettings::get('headerMenu_ua'), true);
        $this->assertSame('/', $items[0]['url']);
        $this->assertSame('/ua/registration', $items[1]['url']);
    }

    public function test_admin_list_requires_authentication(): void
    {
        ProductRegistration::create([
            'first_name' => 'A', 'last_name' => 'B', 'phone' => '+38 (067) 123-45-67', 'email' => 'a@b.com',
            'city' => 'Київ', 'serial_number' => 'S1', 'purchase_date' => '2025-01-01', 'price' => 1,
            'purchase_place' => 'X',
        ]);

        $this->get(route('admin.productRegistration.index'))->assertRedirect();
        $this->get(route('admin.productRegistration.show', 1))->assertRedirect();
        $this->delete(route('admin.productRegistration.destroy', 1))->assertRedirect();
        $this->assertDatabaseCount('product_registrations', 1);
    }
}
