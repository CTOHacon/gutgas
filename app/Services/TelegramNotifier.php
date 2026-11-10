<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Sends HTML messages to the configured Telegram chat.
 * A Telegram outage must never break the caller, so failures are logged and swallowed.
 */
class TelegramNotifier
{
    private const MAX_VALUE_LENGTH = 1000;

    /**
     * Send an already formatted HTML message.
     */
    public function send(string $html): bool
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (empty($token) || empty($chatId)) {
            Log::warning('Telegram notification skipped: bot token or chat id is not configured');
            return false;
        }

        try {
            $response = Http::timeout(5)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'                  => $chatId,
                'text'                     => $html,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if ($response->failed()) {
                Log::error('Telegram notification failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Telegram notification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Build a message: bold title, sections of "label: value" lines, optional links and timestamp.
     * Section keys (non-numeric) become underlined section headings. Empty values are skipped.
     * $url is a single URL (labelled by $urlLabel) or a [label => url] map; empty urls are skipped.
     * All user supplied text is HTML-escaped.
     *
     * @param array<int|string, array<string, mixed>> $sections
     * @param string|array<string, string|null>|null $url
     */
    public static function format(string $title, array $sections, string|array|null $url = null, ?string $urlLabel = null): string
    {
        $blocks = ['<b>' . self::escape($title) . '</b>'];

        foreach ($sections as $heading => $lines) {
            $block = [];
            foreach ($lines as $label => $value) {
                if ($value === null || $value === '') {
                    continue;
                }
                $value = mb_strimwidth((string) $value, 0, self::MAX_VALUE_LENGTH, '…');
                $block[] = '<b>' . self::escape((string) $label) . ':</b> ' . self::escape($value);
            }
            if (empty($block)) {
                continue;
            }
            if (is_string($heading)) {
                array_unshift($block, '<u>' . self::escape($heading) . '</u>');
            }
            $blocks[] = implode("\n", $block);
        }

        $links = is_array($url) ? $url : ($url ? [$urlLabel ?? $url => $url] : []);
        $anchors = [];
        foreach ($links as $label => $href) {
            if (blank($href)) {
                continue;
            }
            $anchors[] = '<a href="' . htmlspecialchars($href, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '">'
                . self::escape((string) $label) . '</a>';
        }
        if ($anchors) {
            $blocks[] = implode("\n", $anchors);
        }

        $blocks[] = '<i>' . now()->format('d/m/Y H:i') . '</i>';

        return implode("\n\n", $blocks);
    }

    public static function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
