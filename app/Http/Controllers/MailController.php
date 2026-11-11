<?php

namespace App\Http\Controllers;

use App\Services\TelegramNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function __construct(private TelegramNotifier $telegram)
    {
    }

    public function consultationMail(Request $request)
    {
        $data = request()->validate([
            'name'     => 'required|string|max:255|min:3',
            'phone'    => 'required|string|max:32',
            'email'    => 'nullable|email',
            'question' => 'nullable|string'
        ]);

        try {
            Mail::send('emails.consultation', $data, function ($message) {
                $message->from('form-manager@gutgas.eu', 'Gutgas Sale manager');
                $message->to(env('ADMIN_EMAIL', 'sale@gutgas.eu'));
                $message->subject('Заявка на консультацію');
            });
        } catch (\Exception $e) {
            throw $e;
        }

        $this->telegram->send(TelegramNotifier::format('✅ Запит на консультацію', [
            [
                '👤 Імʼя'    => $data['name'] ?? null,
                '📞 Телефон' => $data['phone'] ?? null,
                '📧 Email'   => $data['email'] ?? null,
            ],
            [
                '📝 Питання' => $data['question'] ?? null,
            ],
        ]));

        return redirect()->route('thankYou');
    }

    public function requestCallMail(Request $request)
    {
        $data = request()->validate([
            'phone' => 'required|string|max:32',
        ]);

        Mail::send('emails.consultation', $data, function ($message) {
            $message->from('form-manager@gutgas.eu', 'Gutgas Sale manager');
            $message->to(env('ADMIN_EMAIL', 'sale@gutgas.eu'));
            $message->subject('Заявка на дзвінок');
        });

        $this->telegram->send(TelegramNotifier::format('✅ Запит на дзвінок', [
            [
                '📞 Телефон' => $data['phone'],
            ],
        ]));

        return redirect()->route('thankYou');
    }

}
