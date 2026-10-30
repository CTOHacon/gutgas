<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRegistration\StoreRequest;
use App\Models\ProductRegistration;
use App\Services\TelegramNotifier;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductRegistrationController extends Controller
{
    public function __construct(private TelegramNotifier $telegram)
    {
    }

    public function create(): Response
    {
        return Inertia::render('ProductRegistration', [
            'registrationTranslations' => trans('product-registration'),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        // Spam bot: pretend everything went fine, store and notify nothing.
        if ($request->isHoneypotFilled()) {
            return redirect()->back();
        }

        $registration = ProductRegistration::create([
            ...$request->safe()->except('locale'),
            'subscription_consent' => $request->boolean('subscription_consent'),
            'locale'               => $request->input('locale') ?: app()->getLocale(),
            'ip'                   => $request->ip(),
        ]);

        $this->telegram->send($this->telegramMessage($registration));

        return redirect()->back();
    }

    private function telegramMessage(ProductRegistration $registration): string
    {
        return TelegramNotifier::format(
            "🛡 Реєстрація продукту #{$registration->id}",
            [
                '👤 Клієнт' => [
                    "Ім'я"    => trim($registration->first_name . ' ' . $registration->last_name),
                    'Телефон' => $registration->phone,
                    'Email'   => $registration->email,
                    'Місто'   => $registration->city,
                    'Адреса'  => $registration->address,
                ],
                '🔥 Продукт' => [
                    'Модель'            => $registration->model,
                    'Серійний номер'    => $registration->serial_number,
                    'Вартість'          => number_format($registration->price, 0, '', ' ') . ' грн',
                    'Дата покупки'      => $registration->purchase_date->format('d/m/Y'),
                ],
                '🏬 Місце покупки' => [
                    'Магазин, місто' => $registration->purchase_place,
                ],
                '📬 Згода' => [
                    'Підписка на новини' => $registration->subscription_consent ? 'так' : 'ні',
                ],
            ],
            route('admin.productRegistration.show', $registration->id),
            'Відкрити в адмінці'
        );
    }
}
