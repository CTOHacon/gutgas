<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Interfaces\EmailServiceInterface;
use App\Jobs\ProcessNewOrder;
use App\Models\Order;
use App\Models\Product;
use App\Services\SalesDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    protected EmailServiceInterface $emailService;

    public function __construct(EmailServiceInterface $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexAdmin(Request $request, string $search = '')
    {
        if (request()->routeIs('admin.order.search') && empty($search)) {
            return redirect()->route('admin.order.index');
        }

        $show_compleated = $request->get('show_compleated') ?? '0';

        $orders = Order::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->where('compleated', $show_compleated)
            ->where('client_name', 'LIKE', "%{$search}%")
            ->orWhere('id', $search);

        return Inertia::render('Admin/Order/Index', [
            'orders'         => $orders->paginate(10),
            'showCompleated' => $request->get('show_compleated'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'client_name'      => $data['client_name'],
                'client_surname'   => $data['client_surname'],
                'client_phone'     => $data['client_phone'],
                'client_email'     => $data['client_email'],
                'client_message'   => $data['client_message'] ?? null,
                // Prices/names come from the DB, never from the browser
                'cart_content'     => $this->buildCartSnapshot($data['cart_content']),
                'status_name'      => Order::DEFAULT_STATUS_NAME,
                'status_color'     => Order::DEFAULT_STATUS_COLOR,
                'compleated'       => false,
                'locale'           => $data['locale'] ?? app()->getLocale(),
            ] + $this->shippingAttributes($data));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        // Send emails using the service
        $this->emailService->sendOrderNotification($order);
        $this->emailService->sendOrderConfirmation($order);

        // CRM push + Telegram run after the response is sent; failures never reach the customer
        ProcessNewOrder::dispatch($order)->afterResponse();

        return redirect()->route('thankYou')
            ->with('order', $order)->with('thankYouTranslations', trans('thank-you'))
            ->with('analytics_order_id', $order->id);
    }

    /**
     * Shipping columns for the chosen method; fields that do not belong to
     * the method are dropped.
     *
     * @param array<string, mixed> $data validated request data
     * @return array<string, string|null>
     */
    private function shippingAttributes(array $data): array
    {
        $method = $data['shipping_method'];
        $isNp   = $method !== Order::SHIPPING_METHOD_OTHER;
        $hasWarehouse = in_array($method, [
            Order::SHIPPING_METHOD_DEPARTMENT,
            Order::SHIPPING_METHOD_POSTAL_MACHINE,
        ], true);

        return [
            'shipping_method'        => $method,
            'shipping_city'          => $isNp ? $data['shipping_city'] : null,
            'shipping_city_ref'      => $isNp ? $data['shipping_city_ref'] : null,
            'shipping_area'          => $isNp ? ($data['shipping_area'] ?? null) : null,
            'shipping_warehouse'     => $hasWarehouse ? $data['shipping_warehouse'] : null,
            'shipping_warehouse_ref' => $hasWarehouse ? $data['shipping_warehouse_ref'] : null,
            'shipping_address'       => $method === Order::SHIPPING_METHOD_COURIER ? $data['shipping_address'] : null,
            'shipping_message'       => $method === Order::SHIPPING_METHOD_OTHER ? $data['shipping_message'] : null,
        ];
    }

    /**
     * Rebuild the cart from Product records. Only id and quantity are read
     * from the client; duplicate ids are merged.
     *
     * @param array<int, array{id: int|string, quantity: int|string}> $items
     * @return array<int, array<string, mixed>>
     * @throws ValidationException
     */
    private function buildCartSnapshot(array $items): array
    {
        $quantities = [];
        foreach ($items as $item) {
            $id = (int) $item['id'];
            $quantities[$id] = min(999, ($quantities[$id] ?? 0) + (int) $item['quantity']);
        }

        $products = Product::whereIn('id', array_keys($quantities))->get()->keyBy('id');

        $snapshot = [];
        foreach ($quantities as $id => $quantity) {
            $product = $products->get($id);

            if (!$product || $product->price === null) {
                throw ValidationException::withMessages([
                    'cart_content' => __('validation.cartProductUnavailable'),
                ]);
            }

            $snapshot[] = [
                'id'          => $product->id,
                'name'        => $product->getTranslations('name'),
                'sku'         => $product->sku,
                'price'       => $product->price,
                'old_price'   => $product->old_price,
                'quantity'    => $quantity,
                'media_file'  => $product->mediaFile?->thumbnail
                    ? ['thumbnail' => ['url' => $product->mediaFile->thumbnail->url]]
                    : null,
            ];
        }

        return $snapshot;
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return Inertia::render('Admin/Order/Edit', [
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Order $order)
    {
        DB::beginTransaction();
        try {
            $order->update($request->validated());

            DB::commit();
            return redirect()->route('admin.order.edit', $order->id)->with('message', [
                'type' => 'success',
                'text' => 'Order updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Manually (re)send the order to the CRM.
     */
    public function pushToCrm(Order $order, SalesDrive $salesDrive)
    {
        if (!$salesDrive->isConfigured()) {
            $message = ['type' => 'error', 'text' => 'CRM не налаштовано'];
        } elseif ($salesDrive->push($order)) {
            $message = ['type' => 'success', 'text' => 'Замовлення відправлено в CRM'];
        } else {
            $message = ['type' => 'error', 'text' => 'Не вдалося відправити в CRM: ' . $order->fresh()->crm_error];
        }

        return redirect()->route('admin.order.edit', $order->id)->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {
            $order->delete();

            DB::commit();
            return redirect()->route('admin.order.index')->with('message', [
                'type' => 'success',
                'text' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
