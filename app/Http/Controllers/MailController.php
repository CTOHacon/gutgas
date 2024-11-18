<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function consultationMail(Request $request)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255|min:3',
            // phone should be +38 (###) ###-##-## where # is a digit
            'phone' => 'required|regex:/^\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
            'email' => 'nullable|email',
            'question' => 'nullable|string'
        ]);

        try {
            Mail::send('emails.consultation', $data, function ($message) {
                $message->from('form-manager@gutgas.eu', 'Gutgas Sale manager');
                $message->to('sale@gutgas.eu');
                $message->subject('Заявка на консультацію');
            });
        } catch (\Exception $e) {
            throw $e;
        }

        $messageText = "**✅ Запит на консультацію**\n\n";
        if (isset($data['name']))
            $messageText .= "👤 " . $data['name'] . "\n";
        if (isset($data['phone']))
            $messageText .= "📞 " . $data['phone'] . "\n";
        if (isset($data['email']))
            $messageText .= "📧 " . $data['email'] . "\n\n";
        $messageText .= "———————————————\n\n";
        if (isset($data['question']))
            $messageText .= "📝 " . $data['question'] . "\n\n";
        $messageText .= "\n\n`" . date('d/m/Y') . "    " . date('H:i') . "`";

        $data = [
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => $messageText,
            'parse_mode' => 'Markdown'
        ];
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage?" . http_build_query($data);
        file_get_contents($url);

        return redirect()->route('thankYou');
    }

    public function requestCallMail(Request $request)
    {
        $data = request()->validate([
            'phone' => 'required|min:9|max:9',
        ]);

        Mail::send('emails.consultation', $data, function ($message) {
            $message->from('form-manager@gutgas.eu', 'Gutgas Sale manager');
            $message->to('sale@gutgas.eu');
            $message->subject('Заявка на дзвінок');
        });

        $messageText = "**✅ Запит на консультацію**\n\n";
        $messageText .= "📞 " . $data['phone'] . "\n\n";
        $messageText .= "———————————————\n\n";
        $messageText .= "\n\n`" . date('d/m/Y') . "    " . date('H:i') . "`";

        $data = [
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => $messageText,
            'parse_mode' => 'Markdown'
        ];
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage?" . http_build_query($data);
        file_get_contents($url);

        return redirect()->route('thankYou');
    }
}
