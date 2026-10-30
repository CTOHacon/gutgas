<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NovaPoshtaTest extends TestCase
{
    private const CITY_REF = '8d5a980d-391c-11dd-90d9-001a92567626';

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.novaposhta.key' => 'test-key']);
    }

    public function test_settlements_are_mapped_to_options(): void
    {
        Http::fake([
            'api.novaposhta.ua/*' => Http::response([
                'success' => true,
                'data'    => [[
                    'TotalCount' => 1,
                    'Addresses'  => [[
                        'Present'         => 'м. Київ, Київська обл.',
                        'MainDescription' => 'Київ',
                        'Area'            => 'Київська',
                        'Region'          => '',
                        'DeliveryCity'    => self::CITY_REF,
                    ]],
                ]],
                'errors'  => [],
            ]),
        ]);

        $this->getJson('/api/nova-poshta/settlements?q=' . rawurlencode('Київ'))
            ->assertOk()
            ->assertExactJson(['options' => [[
                'value'   => 'Київ',
                'label'   => 'м. Київ, Київська обл.',
                'cityRef' => self::CITY_REF,
                'area'    => 'Київська',
            ]]]);

        Http::assertSent(fn($request) => $request['apiKey'] === 'test-key'
            && $request['calledMethod'] === 'searchSettlements'
            && $request['methodProperties']['CityName'] === 'Київ');
    }

    public function test_short_settlement_query_returns_empty_without_api_call(): void
    {
        Http::fake();

        $this->getJson('/api/nova-poshta/settlements?q=' . rawurlencode('К'))
            ->assertOk()
            ->assertExactJson(['options' => []]);

        Http::assertNothingSent();
    }

    public function test_settlements_are_cached(): void
    {
        Http::fake(['api.novaposhta.ua/*' => Http::response(['success' => true, 'data' => [], 'errors' => []])]);

        $this->getJson('/api/nova-poshta/settlements?q=' . rawurlencode('Київ'))->assertOk();
        $this->getJson('/api/nova-poshta/settlements?q=' . rawurlencode('Київ'))->assertOk();

        Http::assertSentCount(1);
    }

    public function test_warehouses_filter_out_postomats_for_departments(): void
    {
        Http::fake([
            'api.novaposhta.ua/*' => Http::response([
                'success' => true,
                'data'    => [
                    ['Description' => 'Відділення №1', 'Ref' => 'ref-1', 'CategoryOfWarehouse' => 'Branch'],
                    ['Description' => 'Поштомат №5', 'Ref' => 'ref-5', 'CategoryOfWarehouse' => 'Postomat'],
                ],
                'errors'  => [],
            ]),
        ]);

        $this->getJson('/api/nova-poshta/warehouses?cityRef=' . self::CITY_REF . '&type=department&q=1')
            ->assertOk()
            ->assertExactJson(['options' => [
                ['value' => 'Відділення №1', 'label' => 'Відділення №1', 'ref' => 'ref-1'],
            ]]);

        Http::assertSent(fn($request) => $request['calledMethod'] === 'getWarehouses'
            && $request['methodProperties']['CityRef'] === self::CITY_REF
            && $request['methodProperties']['FindByString'] === '1'
            && !isset($request['methodProperties']['TypeOfWarehouseRef']));
    }

    public function test_postal_machines_request_postomat_type(): void
    {
        Http::fake([
            'api.novaposhta.ua/*' => Http::response([
                'success' => true,
                'data'    => [['Description' => 'Поштомат №5', 'Ref' => 'ref-5', 'CategoryOfWarehouse' => 'Postomat']],
                'errors'  => [],
            ]),
        ]);

        $this->getJson('/api/nova-poshta/warehouses?cityRef=' . self::CITY_REF . '&type=postal_machine')
            ->assertOk()
            ->assertJsonPath('options.0.ref', 'ref-5');

        Http::assertSent(fn($request) => isset($request['methodProperties']['TypeOfWarehouseRef']));
    }

    public function test_warehouses_require_city_ref(): void
    {
        $this->getJson('/api/nova-poshta/warehouses?type=department')->assertStatus(422);
    }

    public function test_missing_api_key_returns_503(): void
    {
        config(['services.novaposhta.key' => null]);
        Http::fake();

        $this->getJson('/api/nova-poshta/settlements?q=' . rawurlencode('Київ'))
            ->assertStatus(503)
            ->assertJsonStructure(['message']);

        Http::assertNothingSent();
    }

    public function test_api_error_returns_502(): void
    {
        Http::fake(['api.novaposhta.ua/*' => Http::response(['success' => false, 'data' => [], 'errors' => ['x']])]);

        $this->getJson('/api/nova-poshta/settlements?q=' . rawurlencode('Київ'))->assertStatus(502);
    }
}
