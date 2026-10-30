<?php

use App\Models\GlobalSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private const PERMISSIONS = [
        'admin.productRegistration.index',
        'admin.productRegistration.manage',
    ];

    /** global settings key => [menu item title, url] */
    private const MENU_ITEMS = [
        'headerMenu_ua' => ['Реєстрація продукту', '/ua/product-registration'],
        'headerMenu_ru' => ['Регистрация продукта', '/ru/product-registration'],
        'headerMenu_en' => ['Product registration', '/product-registration'],
        'headerMenu'    => ['Реєстрація продукту', '/ua/product-registration'],
    ];

    public function up(): void
    {
        if (Schema::hasTable('permissions') && Schema::hasTable('roles')) {
            foreach (self::PERMISSIONS as $name) {
                Permission::findOrCreate($name, 'web');
            }
            foreach (['admin', 'super'] as $roleName) {
                $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
                $role?->givePermissionTo(self::PERMISSIONS);
            }
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        if (!Schema::hasTable('global_settings')) {
            return;
        }

        foreach (self::MENU_ITEMS as $key => [$title, $url]) {
            $setting = GlobalSettings::where('key', $key)->first();
            if (!$setting) {
                continue;
            }

            $items = json_decode((string) $setting->value, true);
            if (!is_array($items)) {
                continue;
            }

            $exists = collect($items)->contains(
                fn ($item) => str_contains($item['url'] ?? '', 'product-registration')
            );
            if ($exists) {
                continue;
            }

            $items[] = ['title' => $title, 'url' => $url];
            $setting->value = json_encode($items, JSON_UNESCAPED_UNICODE);
            $setting->save();
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            Permission::whereIn('name', self::PERMISSIONS)->delete();
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        if (!Schema::hasTable('global_settings')) {
            return;
        }

        foreach (array_keys(self::MENU_ITEMS) as $key) {
            $setting = GlobalSettings::where('key', $key)->first();
            $items = $setting ? json_decode((string) $setting->value, true) : null;
            if (!is_array($items)) {
                continue;
            }
            $items = array_values(array_filter(
                $items,
                fn ($item) => !str_contains($item['url'] ?? '', 'product-registration')
            ));
            $setting->value = json_encode($items, JSON_UNESCAPED_UNICODE);
            $setting->save();
        }
    }
};
