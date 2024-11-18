<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
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
            'orders' => $orders->paginate(10),
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
            $order = Order::create($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        $data['order_id'] = $order->id;

        try {
            Mail::send('emails.newOrder', $data, function ($message) {
                $message->from('form-manager@gutgas.eu', 'Gutgas Sale manager');
                $message->to('sale@gutgas.eu');
                // $message->to('borysenko.alexander@gmail.com');
                $message->subject('$$$ Нове Замовлення $$$');
            });
        } catch (\Exception $e) {
        }

        try {
            $totalPrice = 0;
            foreach ($order->cart_content as $product) {
                $totalPrice += $product['price'] * $product['quantity'];
            }

            $messageText = "**✅ Нове замовлення №{$order->id}**\n\n";
            $messageText .= "👤 {$order->client_name}\n";
            $messageText .= "💰 {$totalPrice}\n\n";
            $messageText .= "📞 {$order->client_phone}\n";
            $messageText .= "📩 [{$order->client_email}](mailto:{$order->client_email})\n\n";
            $messageText .= "———————————————\n\n";
            $messageText .= "__💬: {$order->client_message}__\n";
            $messageText .= "__🚚: {$order->shipping_message}__\n\n";
            $messageText .= "🛒\n";
            $counter = 1;
            foreach ($order->cart_content as $product) {
                $counter++;
                $messageText .= "{$counter}. {$product['name'][app()->getLocale()]} - {$product['quantity']} шт. - {$product['price']}грн/шт.\n";
            }
            $messageText .= "\n\n`" . date('d/m/Y') . "    " . date('H:i') . "`";
            $data = [
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $messageText,
                'parse_mode' => 'Markdown'
            ];
            $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage?" . http_build_query($data);

            file_get_contents($url);
        } catch (\Exception $e) {
        }

        return redirect()->route('thankYou')
            ->with('order', $order)->with('thankYouTranslations', trans('thank-you'));
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
    public function update(StoreRequest $request, Order $order)
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
