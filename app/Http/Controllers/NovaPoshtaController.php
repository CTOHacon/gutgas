<?php

namespace App\Http\Controllers;

use App\Exceptions\NovaPoshtaException;
use App\Services\NovaPoshta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NovaPoshtaController extends Controller
{
    public function __construct(protected NovaPoshta $novaPoshta)
    {
    }

    /**
     * GET /api/nova-poshta/settlements?q=
     */
    public function settlements(Request $request): JsonResponse
    {
        $q = (string) $request->query('q', '');

        try {
            return response()->json(['options' => $this->novaPoshta->searchSettlements($q)]);
        } catch (NovaPoshtaException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status());
        }
    }

    /**
     * GET /api/nova-poshta/warehouses?cityRef=&type=department|postal_machine&q=
     */
    public function warehouses(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cityRef' => 'required|uuid',
            'type'    => 'nullable|in:department,postal_machine',
            'q'       => 'nullable|string|max:100',
        ]);

        try {
            return response()->json([
                'options' => $this->novaPoshta->warehouses(
                    $data['cityRef'],
                    $data['type'] ?? 'department',
                    $data['q'] ?? ''
                ),
            ]);
        } catch (NovaPoshtaException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status());
        }
    }

}
