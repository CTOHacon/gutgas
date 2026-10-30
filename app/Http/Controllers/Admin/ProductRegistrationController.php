<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductRegistrationController extends Controller
{
    public function index(Request $request, string $search = ''): Response
    {
        $registrations = ProductRegistration::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('serial_number', 'LIKE', "%{$search}%")
                        ->orWhere('id', $search);
                });
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        return Inertia::render('Admin/ProductRegistration/Index', [
            'registrations' => $registrations->paginate(10),
        ]);
    }

    public function show(ProductRegistration $productRegistration): Response
    {
        return Inertia::render('Admin/ProductRegistration/Show', [
            'registration' => $productRegistration,
        ]);
    }

    public function destroy(ProductRegistration $productRegistration): RedirectResponse
    {
        $productRegistration->delete();

        return redirect()->route('admin.productRegistration.index')->with('message', [
            'type' => 'success',
            'text' => 'Реєстрацію видалено',
        ]);
    }
}
