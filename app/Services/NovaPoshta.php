<?php

namespace App\Services;

use App\Exceptions\NovaPoshtaException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NovaPoshta
{
    private const API_URL = 'https://api.novaposhta.ua/v2.0/json/';

    // Nova Poshta's own ref for the "Postomat" warehouse type — lets the API
    // filter postomats itself (big cities have thousands of them).
    private const POSTOMAT_TYPE_REF = 'f9316480-5f2d-425d-bc2c-ac7cd29decf0';

    private const SETTLEMENTS_TTL = 3600;
    private const WAREHOUSES_TTL  = 43200;

    /**
     * Call the Nova Poshta JSON API. The API key never leaves the server.
     *
     * @param string $modelName
     * @param string $calledMethod
     * @param array<string, mixed> $methodProperties
     * @return array<int, mixed>
     * @throws NovaPoshtaException
     */
    public function request(string $modelName, string $calledMethod, array $methodProperties): array
    {
        $apiKey = config('services.novaposhta.key');

        if (empty($apiKey)) {
            throw new NovaPoshtaException('Nova Poshta API key is not configured', 503);
        }

        try {
            $response = Http::timeout(10)->acceptJson()->post(self::API_URL, [
                'apiKey'           => $apiKey,
                'modelName'        => $modelName,
                'calledMethod'     => $calledMethod,
                'methodProperties' => $methodProperties,
            ]);
        } catch (ConnectionException $e) {
            throw new NovaPoshtaException('Nova Poshta API is unreachable', 502);
        }

        if ($response->failed() || !$response->json('success')) {
            throw new NovaPoshtaException('Nova Poshta API error', 502);
        }

        return $response->json('data') ?? [];
    }

    /**
     * Search settlements (cities/villages) by name.
     *
     * @param string $query
     * @return array<int, array{value: string, label: string, cityRef: string, area: string}>
     */
    public function searchSettlements(string $query): array
    {
        $query = trim($query);

        if (mb_strlen($query) < 2) {
            return [];
        }

        return Cache::remember(
            'np:settlements:' . md5(mb_strtolower($query)),
            self::SETTLEMENTS_TTL,
            function () use ($query) {
                $data = $this->request('Address', 'searchSettlements', [
                    'CityName' => $query,
                    'Limit'    => '20',
                    'Page'     => '1',
                ]);

                $addresses = $data[0]['Addresses'] ?? [];

                return array_map(fn(array $address) => [
                    'value'   => $address['MainDescription'] ?? '',
                    'label'   => $address['Present'] ?? '',
                    'cityRef' => $address['DeliveryCity'] ?? '',
                    'area'    => implode(', ', array_filter([
                        $address['Region'] ?? '',
                        $address['Area'] ?? '',
                    ])),
                ], $addresses);
            }
        );
    }

    /**
     * List warehouses of a settlement, filtered by kind (department|postal_machine).
     *
     * @param string $cityRef
     * @param string $type
     * @param string $query
     * @return array<int, array{value: string, label: string, ref: string}>
     */
    public function warehouses(string $cityRef, string $type = 'department', string $query = ''): array
    {
        $isPostomat = $type === 'postal_machine';
        $query      = trim($query);

        return Cache::remember(
            'np:warehouses:' . md5($cityRef . '|' . $type . '|' . mb_strtolower($query)),
            self::WAREHOUSES_TTL,
            function () use ($cityRef, $isPostomat, $query) {
                $properties = [
                    'CityRef'  => $cityRef,
                    'Page'     => '1',
                    'Limit'    => $isPostomat ? '50' : '300',
                    'Language' => 'UA',
                ];

                if ($query !== '') {
                    $properties['FindByString'] = $query;
                }
                if ($isPostomat) {
                    $properties['TypeOfWarehouseRef'] = self::POSTOMAT_TYPE_REF;
                }

                $warehouses = $this->request('AddressGeneral', 'getWarehouses', $properties);

                if (!$isPostomat) {
                    $warehouses = array_filter(
                        $warehouses,
                        fn(array $warehouse) => ($warehouse['CategoryOfWarehouse'] ?? '') !== 'Postomat'
                    );
                }

                return array_map(fn(array $warehouse) => [
                    'value' => $warehouse['Description'] ?? '',
                    'label' => $warehouse['Description'] ?? '',
                    'ref'   => $warehouse['Ref'] ?? '',
                ], array_slice(array_values($warehouses), 0, 50));
            }
        );
    }

}
