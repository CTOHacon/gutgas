<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\SalesDrive;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SalesDriveTest extends TestCase
{
    use RefreshDatabase;

    private const CRM_URL = 'https://crm.test';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.salesdrive.url'         => self::CRM_URL,
            'services.salesdrive.key'         => 'test-key',
            'services.salesdrive.site_option' => 'id_45',
        ]);
    }

    private function crmOk(int $id = 1362)
    {
        return Http::response(['success' => true, 'data' => ['orderId' => $id, 'userId' => 3]]);
    }

    private function order(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'client_name'      => 'Іван',
            'client_surname'   => 'Петренко',
            'client_phone'     => '+38 (050) 123-45-67',
            'client_email'     => 'ivan@example.com',
            'client_message'   => 'Після 18:00',
            'cart_content'     => [[
                'id' => 7, 'name' => ['ua' => 'Балон', 'ru' => 'Баллон'], 'sku' => 'GHCL2425S',
                'price' => 6999, 'old_price' => null, 'quantity' => 2, 'media_file' => null,
            ]],
            'status_name'      => Order::DEFAULT_STATUS_NAME,
            'status_color'     => Order::DEFAULT_STATUS_COLOR,
            'compleated'       => false,
            'shipping_method'  => Order::SHIPPING_METHOD_OTHER,
            'shipping_message' => 'Самовивіз',
        ], $overrides));
    }

    private function basePayload(Order $order): array
    {
        return [
            'getResultData' => 1,
            'fName'         => 'Іван',
            'lName'         => 'Петренко',
            'phone'         => '380501234567',
            'email'         => 'ivan@example.com',
            'externalId'    => (string) $order->id,
            'sajt'          => 'id_45',
            'comment'       => 'Після 18:00',
        ];
    }

    private function products(): array
    {
        return [['id' => 'GHCL2425S', 'sku' => 'GHCL2425S', 'name' => 'Балон', 'costPerItem' => 6999, 'amount' => 2]];
    }

    public function test_payload_for_department(): void
    {
        $order = $this->order([
            'shipping_method' => 'np_department', 'shipping_message' => null,
            'shipping_city' => 'Київ', 'shipping_city_ref' => 'CITY-REF',
            'shipping_warehouse' => 'Відділення №1', 'shipping_warehouse_ref' => 'WH-REF',
        ]);

        $this->assertSame($this->basePayload($order) + [
            'shipping_method' => 'novaposhta',
            'novaposhta'      => ['ServiceType' => 'Warehouse', 'payer' => 'recipient', 'city' => 'CITY-REF', 'WarehouseNumber' => 'WH-REF'],
            'products'        => $this->products(),
        ], app(SalesDrive::class)->orderPayload($order));
    }

    public function test_payload_for_postal_machine(): void
    {
        $order = $this->order([
            'shipping_method' => 'np_postal_machine', 'shipping_message' => null,
            'shipping_city' => 'Київ', 'shipping_city_ref' => 'CITY-REF',
            'shipping_warehouse' => 'Поштомат №5', 'shipping_warehouse_ref' => 'PM-REF',
        ]);

        $payload = app(SalesDrive::class)->orderPayload($order);

        $this->assertSame('novaposhta', $payload['shipping_method']);
        $this->assertSame(
            ['ServiceType' => 'Warehouse', 'payer' => 'recipient', 'city' => 'CITY-REF', 'WarehouseNumber' => 'PM-REF'],
            $payload['novaposhta']
        );
        $this->assertArrayNotHasKey('shipping_address', $payload);
    }

    public function test_payload_for_courier(): void
    {
        $order = $this->order([
            'shipping_method' => 'np_courier', 'shipping_message' => null,
            'shipping_city' => 'Київ', 'shipping_city_ref' => 'CITY-REF',
            'shipping_address' => 'вул. Хрещатик, 1',
        ]);

        $this->assertSame($this->basePayload($order) + [
            'shipping_method'  => 'novaposhta',
            'novaposhta'       => ['ServiceType' => 'Doors', 'payer' => 'recipient', 'city' => 'CITY-REF'],
            'shipping_address' => 'Київ, вул. Хрещатик, 1',
            'products'         => $this->products(),
        ], app(SalesDrive::class)->orderPayload($order));
    }

    public function test_payload_for_other(): void
    {
        $order = $this->order();

        $this->assertSame($this->basePayload($order) + [
            'shipping_address' => 'Самовивіз',
            'products'         => $this->products(),
        ], app(SalesDrive::class)->orderPayload($order));
    }

    public function test_payload_omits_empty_comment_and_sku(): void
    {
        $order = $this->order([
            'client_message' => null,
            'cart_content'   => [['id' => 7, 'name' => ['ru' => 'Баллон'], 'sku' => '', 'price' => 10, 'quantity' => 1]],
        ]);

        $payload = app(SalesDrive::class)->orderPayload($order);

        $this->assertArrayNotHasKey('comment', $payload);
        $this->assertSame([['name' => 'Баллон', 'costPerItem' => 10, 'amount' => 1]], $payload['products']);
    }

    public static function phoneProvider(): array
    {
        return [
            'masked'       => ['+38 (050) 123-45-67', '380501234567'],
            'local'        => ['0501234567', '380501234567'],
            'international' => ['380501234567', '380501234567'],
            'plus'         => ['+380501234567', '380501234567'],
            'no zero'      => ['501234567', '380501234567'],
            'garbage'      => ['12-34', '12-34'],
            'foreign'      => ['+1 202 555 0100', '12025550100'],
            'georgia'      => ['+995 555 12 34 56', '995555123456'],
            'moldova'      => ['+373 (69) 123-456', '37369123456'],
            'kazakhstan'   => ['+7 701 123 45 67', '77011234567'],
        ];
    }

    /** @dataProvider phoneProvider */
    public function test_phone_normalization(string $input, string $expected): void
    {
        $this->assertSame($expected, SalesDrive::normalizePhone($input));
    }

    public function test_successful_push_stores_crm_data_and_sends_headers(): void
    {
        Http::fake([self::CRM_URL . '/*' => $this->crmOk()]);
        $order = $this->order(['crm_error' => 'old']);

        $this->assertTrue(app(SalesDrive::class)->push($order));

        $order->refresh();
        $this->assertSame(1362, $order->crm_order_id);
        $this->assertNotNull($order->crm_sent_at);
        $this->assertNull($order->crm_error);

        Http::assertSent(fn(Request $r) => $r->url() === self::CRM_URL . '/handler/'
            && $r->hasHeader('X-Api-Key', 'test-key')
            && $r['externalId'] === (string) $order->id);
    }

    public function test_second_push_makes_no_http_call(): void
    {
        Http::fake([self::CRM_URL . '/*' => $this->crmOk()]);
        $order = $this->order();

        app(SalesDrive::class)->push($order);
        $this->assertTrue(app(SalesDrive::class)->push($order));

        Http::assertSentCount(1);
    }

    public function test_not_configured_makes_no_http_call(): void
    {
        config(['services.salesdrive.key' => '']);
        Http::fake();
        $order = $this->order();

        $this->assertFalse(app(SalesDrive::class)->push($order));

        Http::assertNothingSent();
        $this->assertNull($order->fresh()->crm_error);
    }

    public static function failureProvider(): array
    {
        return [
            '401'     => [fn() => Http::response(['error' => 'X-Api-Key is invalid'], 401), 'X-Api-Key is invalid'],
            '500'     => [fn() => Http::response('boom', 500), 'HTTP 500'],
            'no flag' => [fn() => Http::response(['success' => false], 200), 'HTTP 200'],
            'timeout' => [fn() => fn() => throw new ConnectionException('timeout'), 'unreachable'],
        ];
    }

    /** @dataProvider failureProvider */
    public function test_failed_push_stores_error_and_does_not_throw(\Closure $response, string $expectedError): void
    {
        Http::fake([self::CRM_URL . '/*' => $response()]);
        $order = $this->order();

        $this->assertFalse(app(SalesDrive::class)->push($order));

        $order->refresh();
        $this->assertNull($order->crm_order_id);
        $this->assertStringContainsString($expectedError, $order->crm_error);
    }

    private function storePayload(): array
    {
        $product = Product::factory()->create(['price' => 1500, 'sku' => 'SKU-1']);

        return [
            'client_name'      => 'Іван',
            'client_surname'   => 'Петренко',
            'client_phone'     => '+38 (067) 123-45-67',
            'client_email'     => 'ivan@example.com',
            'cart_content'     => [['id' => $product->id, 'quantity' => 1]],
            'shipping_method'  => 'other',
            'shipping_message' => 'Київ',
        ];
    }

    public function test_checkout_pushes_order_to_crm_after_response(): void
    {
        Http::fake([
            self::CRM_URL . '/*' => $this->crmOk(555),
            '*'                  => Http::response(['ok' => true]),
        ]);

        $this->post('/order/store', $this->storePayload())->assertRedirect(route('thankYou'));

        $order = Order::first();
        $this->assertSame(555, $order->crm_order_id);
        Http::assertSent(fn(Request $r) => $r->url() === self::CRM_URL . '/handler/'
            && $r['phone'] === '380671234567'
            && $r['products'][0]['id'] === 'SKU-1');
    }

    public function test_order_url_points_to_crm_order_card(): void
    {
        config(['services.salesdrive.form_id' => 1]);

        $this->assertSame(
            self::CRM_URL . '/ua/index.html?formId=1#/order/update/1439',
            app(SalesDrive::class)->orderUrl(1439)
        );
    }

    public function test_telegram_message_has_crm_number_and_link(): void
    {
        config(['services.telegram.bot_token' => 'tg', 'services.telegram.chat_id' => '1']);
        Http::fake([
            self::CRM_URL . '/*' => $this->crmOk(1439),
            '*'                  => Http::response(['ok' => true]),
        ]);

        $this->post('/order/store', $this->storePayload())->assertRedirect(route('thankYou'));

        Http::assertSent(fn(Request $r) => str_contains($r->url(), 'api.telegram.org')
            && str_contains($r['text'], 'Заявка:</b> №1439')
            && str_contains($r['text'], 'href="' . self::CRM_URL . '/ua/index.html?formId=1#/order/update/1439"'));
    }

    public function test_telegram_message_reports_crm_failure(): void
    {
        config(['services.telegram.bot_token' => 'tg', 'services.telegram.chat_id' => '1']);
        Http::fake([
            self::CRM_URL . '/*' => Http::response(['error' => 'X-Api-Key is invalid'], 401),
            '*'                  => Http::response(['ok' => true]),
        ]);

        $this->post('/order/store', $this->storePayload())->assertRedirect(route('thankYou'));

        Http::assertSent(fn(Request $r) => str_contains($r->url(), 'api.telegram.org')
            && str_contains($r['text'], 'Не відправлено')
            && !str_contains($r['text'], 'Відкрити в CRM'));
    }

    public function test_checkout_succeeds_when_crm_is_down(): void
    {
        Http::fake([
            self::CRM_URL . '/*' => Http::response(['error' => 'X-Api-Key is invalid'], 401),
            '*'                  => Http::response(['ok' => true]),
        ]);

        $this->post('/order/store', $this->storePayload())->assertRedirect(route('thankYou'));

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertNull($order->crm_order_id);
        $this->assertNotNull($order->crm_error);
    }

    public function test_checkout_succeeds_on_crm_timeout(): void
    {
        Http::fake([
            self::CRM_URL . '/*' => fn() => throw new ConnectionException('timeout'),
            '*'                  => Http::response(['ok' => true]),
        ]);

        $this->post('/order/store', $this->storePayload())->assertRedirect(route('thankYou'));

        $this->assertNotNull(Order::first()->crm_error);
    }

    public function test_command_without_options_pushes_nothing(): void
    {
        Http::fake();
        $this->order();

        $this->artisan('crm:push-orders')->assertSuccessful();

        Http::assertNothingSent();
    }

    public function test_command_failed_pushes_only_failed_orders(): void
    {
        Http::fake([self::CRM_URL . '/*' => $this->crmOk(900)]);
        $failed  = $this->order(['crm_error' => 'boom']);
        $fresh   = $this->order();
        $sent    = $this->order(['crm_order_id' => 5, 'crm_error' => 'stale']);

        $this->artisan('crm:push-orders', ['--failed' => true])->assertSuccessful();

        Http::assertSentCount(1);
        $this->assertSame(900, $failed->fresh()->crm_order_id);
        $this->assertNull($fresh->fresh()->crm_order_id);
        $this->assertSame(5, $sent->fresh()->crm_order_id);
    }

    public function test_command_id_pushes_given_orders_only(): void
    {
        Http::fake([self::CRM_URL . '/*' => $this->crmOk(901)]);
        $a = $this->order();
        $b = $this->order();

        $this->artisan('crm:push-orders', ['--id' => [$a->id]])->assertSuccessful();

        Http::assertSentCount(1);
        $this->assertSame(901, $a->fresh()->crm_order_id);
        $this->assertNull($b->fresh()->crm_order_id);
    }

    private function admin(): User
    {
        $this->seed(RoleAndPermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_admin_retry_requires_auth_and_permission(): void
    {
        Http::fake();
        $order = $this->order();

        $this->post(route('admin.order.crm', $order->id))->assertRedirect();

        $this->seed(RoleAndPermissionSeeder::class);
        $this->actingAs(User::factory()->create())
            ->post(route('admin.order.crm', $order->id))
            ->assertForbidden();

        Http::assertNothingSent();
        $this->assertNull($order->fresh()->crm_order_id);
    }

    public function test_admin_can_retry_push(): void
    {
        Http::fake([self::CRM_URL . '/*' => $this->crmOk(777)]);
        $order = $this->order(['crm_error' => 'boom']);

        $this->actingAs($this->admin())
            ->post(route('admin.order.crm', $order->id))
            ->assertRedirect(route('admin.order.edit', $order->id))
            ->assertSessionHas('message.type', 'success');

        $this->assertSame(777, $order->fresh()->crm_order_id);
        $this->assertNull($order->fresh()->crm_error);
    }

    public function test_admin_retry_reports_failure(): void
    {
        Http::fake([self::CRM_URL . '/*' => Http::response('x', 500)]);
        $order = $this->order();

        $this->actingAs($this->admin())
            ->post(route('admin.order.crm', $order->id))
            ->assertSessionHas('message.type', 'error');

        $this->assertNull($order->fresh()->crm_order_id);
    }
}
