<?php

use App\Models\GlobalSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const MENU_KEYS = ['headerMenu_ua', 'headerMenu_ru', 'headerMenu_en', 'headerMenu'];

    public function up(): void
    {
        $this->replaceInMenus('/product-registration', '/registration');
    }

    public function down(): void
    {
        $this->replaceInMenus('/registration', '/product-registration');
    }

    private function replaceInMenus(string $from, string $to): void
    {
        if (!Schema::hasTable('global_settings')) {
            return;
        }

        foreach (self::MENU_KEYS as $key) {
            $setting = GlobalSettings::where('key', $key)->first();
            $items = $setting ? json_decode((string) $setting->value, true) : null;
            if (!is_array($items)) {
                continue;
            }

            $items = array_map(function ($item) use ($from, $to) {
                if (is_array($item) && isset($item['url']) && str_ends_with($item['url'], $from)) {
                    $item['url'] = substr($item['url'], 0, -strlen($from)) . $to;
                }
                return $item;
            }, $items);

            $setting->value = json_encode($items, JSON_UNESCAPED_UNICODE);
            $setting->save();
        }
    }
};
