<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRegistration\StoreRequest;
use App\Jobs\SendProductRegistrationConfirmation;
use App\Models\ProductRegistration;
use App\Services\TelegramNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

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

        // After the response: the customer never waits on SMTP, and a mail failure is only logged.
        SendProductRegistrationConfirmation::dispatch($registration)->afterResponse();

        return redirect()->back();
    }

    /**
     * Target of the signed link in the confirmation email. Idempotent: only the first
     * valid click sets confirmed_at and notifies Telegram; later clicks just show the result.
     */
    public function confirm(Request $request): HttpResponse
    {
        $registration = $request->hasValidRelativeSignature()
            ? ProductRegistration::find($request->route('registration'))
            : null;

        if (!$registration) {
            return $this->confirmPage(false)->toResponse($request)->setStatusCode(403);
        }

        // Conditional update so double clicks / parallel requests confirm (and notify) once.
        $justConfirmed = ProductRegistration::whereKey($registration->id)
            ->whereNull('confirmed_at')
            ->update(['confirmed_at' => now()]) === 1;

        if ($justConfirmed) {
            $registration->refresh();
            $this->telegram->send(self::telegramConfirmedMessage($registration));
        }

        return $this->confirmPage(true)->toResponse($request);
    }

    private function confirmPage(bool $confirmed): Response
    {
        return Inertia::render('ProductRegistrationConfirm', [
            'confirmed'                => $confirmed,
            'registrationTranslations' => trans('product-registration'),
        ]);
    }

    public static function telegramConfirmedMessage(ProductRegistration $registration): string
    {
        return TelegramNotifier::format(
            "✅ Реєстрацію #{$registration->id} підтверджено",
            [
                [
                    'Клієнт'         => trim($registration->first_name . ' ' . $registration->last_name),
                    'Email'          => $registration->email,
                    'Серійний номер' => $registration->serial_number,
                ],
            ],
            route('admin.productRegistration.show', $registration->id),
            'Відкрити в адмінці'
        );
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
                [
                    'Статус' => 'очікує підтвердження email',
                ],
            ],
            route('admin.productRegistration.show', $registration->id),
            'Відкрити в адмінці'
        );
    }
}
