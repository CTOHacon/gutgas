<?php

use App\Models\GlobalSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const CATALOG_TITLES = [
        'ua' => 'Каталог',
        'ru' => 'Каталог',
        'ka' => 'კატალოგი',
        'en' => 'Catalog',
        'ro' => 'Catalog',
        'kk' => 'Каталог',
    ];

    private const OLD_CATALOG_TITLES = [
        'ua' => 'Каталог балонів',
        'ru' => 'Каталог баллонов',
    ];

    private const REGISTRATION_TITLES = [
        'ua' => 'Реєстрація продукту',
        'ru' => 'Регистрация продукта',
        'ka' => 'პროდუქტის რეგისტრაცია',
        'en' => 'Product registration',
        'ro' => 'Înregistrarea produsului',
        'kk' => 'Өнімді тіркеу',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('global_settings')) {
            return;
        }

        foreach ($this->headerKeys() as $key) {
            $locale = $this->localeOf($key);
            $items = $this->read($key);
            if ($items === null) {
                continue;
            }

            $removed = null;
            $kept = [];
            foreach ($items as $item) {
                $url = is_array($item) ? (string) ($item['url'] ?? '') : '';
                if ($url !== '' && str_ends_with($url, '/registration')) {
                    $removed ??= $item;
                    continue;
                }
                if ($url !== '' && str_ends_with($url, '/catalog') && isset(self::CATALOG_TITLES[$locale])) {
                    $item['title'] = self::CATALOG_TITLES[$locale];
                }
                $kept[] = $item;
            }

            $this->write($key, $kept);
            $this->addToFooter($locale, $removed);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('global_settings')) {
            return;
        }

        foreach ($this->headerKeys() as $key) {
            $locale = $this->localeOf($key);
            $items = $this->read($key);
            if ($items === null) {
                continue;
            }

            foreach ($items as &$item) {
                $url = is_array($item) ? (string) ($item['url'] ?? '') : '';
                if ($url !== '' && str_ends_with($url, '/catalog') && isset(self::OLD_CATALOG_TITLES[$locale])) {
                    $item['title'] = self::OLD_CATALOG_TITLES[$locale];
                }
            }
            unset($item);

            $registration = $this->removeFromFooter($locale);
            $hasRegistration = false;
            foreach ($items as $item) {
                if (is_array($item) && str_ends_with((string) ($item['url'] ?? ''), '/registration')) {
                    $hasRegistration = true;
                }
            }
            if ($registration !== null && !$hasRegistration) {
                $items[] = $registration;
            }

            $this->write($key, $items);
        }
    }

    /** @return string[] */
    private function headerKeys(): array
    {
        $keys = GlobalSettings::where('key', 'like', 'headerMenu%')->pluck('key')->all();

        return array_values(array_filter(
            $keys,
            fn ($k) => $k === 'headerMenu' || str_starts_with($k, 'headerMenu_')
        ));
    }

    private function localeOf(string $key): ?string
    {
        return str_contains($key, '_') ? substr($key, strpos($key, '_') + 1) : null;
    }

    private function footerKey(?string $locale): string
    {
        return $locale === null ? 'footerMenu' : "footerMenu_{$locale}";
    }

    private function read(string $key): ?array
    {
        $setting = GlobalSettings::where('key', $key)->first();
        $items = $setting ? json_decode((string) $setting->value, true) : null;

        return is_array($items) ? $items : null;
    }

    private function write(string $key, array $items): void
    {
        $setting = GlobalSettings::where('key', $key)->first();
        if ($setting) {
            $setting->value = json_encode(array_values($items), JSON_UNESCAPED_UNICODE);
            $setting->save();
        }
    }

    private function addToFooter(?string $locale, ?array $removed): void
    {
        $key = $this->footerKey($locale);
        $groups = $this->read($key);
        if ($groups === null || $groups === []) {
            return;
        }

        $url = !empty($removed['url']) ? $removed['url'] : ($locale === null ? '/registration' : "/{$locale}/registration");
        $title = !empty($removed['title']) ? $removed['title'] : (self::REGISTRATION_TITLES[$locale] ?? 'Product registration');

        $target = null;
        foreach ($groups as $i => $group) {
            foreach (($group['items'] ?? []) as $item) {
                if (!is_array($item)) {
                    continue;
                }
                if (($item['url'] ?? null) === $url) {
                    return;
                }
                if ($target === null && str_ends_with((string) ($item['url'] ?? ''), '/delivery')) {
                    $target = $i;
                }
            }
        }
        $target ??= array_key_last($groups);

        $groups[$target]['items'] = array_values($groups[$target]['items'] ?? []);
        $groups[$target]['items'][] = ['title' => $title, 'url' => $url];

        $this->write($key, $groups);
    }

    /** Removes the registration item from the footer and returns it (best effort). */
    private function removeFromFooter(?string $locale): ?array
    {
        $key = $this->footerKey($locale);
        $groups = $this->read($key);
        if ($groups === null) {
            return null;
        }

        $removed = null;
        foreach ($groups as &$group) {
            $items = [];
            foreach (($group['items'] ?? []) as $item) {
                if (is_array($item) && str_ends_with((string) ($item['url'] ?? ''), '/registration')) {
                    $removed ??= $item;
                    continue;
                }
                $items[] = $item;
            }
            $group['items'] = $items;
        }
        unset($group);

        $this->write($key, $groups);

        return $removed;
    }
};
