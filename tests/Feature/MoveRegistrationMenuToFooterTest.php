<?php

namespace Tests\Feature;

use App\Models\GlobalSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoveRegistrationMenuToFooterTest extends TestCase
{
    use RefreshDatabase;

    private const FILE = 'migrations/2026_11_02_120000_move_registration_menu_item_to_footer.php';

    private function migration(): object
    {
        return require __DIR__ . '/../../database/' . self::FILE;
    }

    private function seedUa(): void
    {
        GlobalSettings::set('headerMenu_ua', [
            ['title' => 'Головна', 'url' => '/ua'],
            ['title' => 'Каталог балонів', 'url' => '/ua/catalog'],
            ['title' => 'Реєстрація продукту', 'url' => '/ua/registration'],
        ]);
        GlobalSettings::set('footerMenu_ua', [
            ['name' => 'Головна', 'items' => [['title' => 'Головна', 'url' => '/ua']]],
            ['name' => 'Інформація', 'items' => [
                ['title' => 'Доставка і оплата', 'url' => '/ua/delivery'],
            ]],
            ['name' => 'Інше', 'items' => [['title' => 'X', 'url' => '/ua/x']]],
        ]);
    }

    private function menu(string $key): array
    {
        return json_decode(GlobalSettings::get($key), true);
    }

    public function test_header_item_removed_and_catalog_renamed(): void
    {
        $this->seedUa();
        $this->migration()->up();

        $header = $this->menu('headerMenu_ua');
        $this->assertCount(2, $header);
        $this->assertSame('Каталог', $header[1]['title']);
        $this->assertNotContains('/ua/registration', array_column($header, 'url'));
    }

    public function test_footer_item_added_once_to_delivery_group(): void
    {
        $this->seedUa();
        $migration = $this->migration();
        $migration->up();
        $migration->up();

        $footer = $this->menu('footerMenu_ua');
        $this->assertCount(1, $footer[0]['items']);
        $this->assertCount(2, $footer[1]['items']);
        $this->assertSame(
            ['title' => 'Реєстрація продукту', 'url' => '/ua/registration'],
            $footer[1]['items'][1]
        );
        $this->assertCount(1, $footer[2]['items']);
    }

    public function test_georgian_locale_uses_georgian_labels(): void
    {
        GlobalSettings::set('headerMenu_ka', [
            ['title' => 'მთავარი', 'url' => '/ka'],
            ['title' => 'ბალონები', 'url' => '/ka/catalog'],
        ]);
        GlobalSettings::set('footerMenu_ka', [
            ['name' => 'ინფო', 'items' => [['title' => 'მიწოდება', 'url' => '/ka/delivery']]],
        ]);

        $this->migration()->up();

        $this->assertSame('კატალოგი', $this->menu('headerMenu_ka')[1]['title']);
        $this->assertSame(
            ['title' => 'პროდუქტის რეგისტრაცია', 'url' => '/ka/registration'],
            $this->menu('footerMenu_ka')[0]['items'][1]
        );
    }

    public function test_missing_keys_are_fine(): void
    {
        $this->migration()->up();
        $this->migration()->down();

        GlobalSettings::set('headerMenu_ru', [['title' => 'Каталог баллонов', 'url' => '/ru/catalog']]);
        $this->migration()->up();

        $this->assertNull(GlobalSettings::get('footerMenu_ru'));
        $this->assertSame('Каталог', $this->menu('headerMenu_ru')[0]['title']);
    }

    public function test_down_restores_menu(): void
    {
        $this->seedUa();
        $migration = $this->migration();
        $migration->up();
        $migration->down();

        $header = $this->menu('headerMenu_ua');
        $this->assertSame('Каталог балонів', $header[1]['title']);
        $this->assertSame('/ua/registration', $header[2]['url']);
        $this->assertCount(1, $this->menu('footerMenu_ua')[1]['items']);
    }
}
