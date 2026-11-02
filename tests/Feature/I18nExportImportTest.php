<?php

namespace Tests\Feature;

use App\Models\GlobalSettings;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class I18nExportImportTest extends TestCase
{
    use RefreshDatabase;

    private string $dir;
    private string $file;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir() . '/i18n-test-' . uniqid();
        mkdir($this->dir);
        $this->file = $this->dir . '/export.json';
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->dir . '/*'));
        rmdir($this->dir);
        parent::tearDown();
    }

    private function seedContent(): array
    {
        $product = Product::factory()->create([
            'name' => ['ua' => 'Газовий котел'],
            'description' => ['ua' => '<a href="/ua/catalog">Каталог</a> https://gutgas.eu/ua/about'],
        ]);
        $page = Page::create([
            'title' => ['ua' => 'Головна', 'xx' => 'Already translated'],
            'content' => ['ua' => 'Зміст'],
            'type' => 'SimplePage',
        ]);
        $page->setMeta('heroTitle_ua', 'Заголовок');
        $page->setMeta('cards_ua', [['title' => 'Картка', 'html' => '<a href="/ua/x">x</a>']], 'collection');

        GlobalSettings::set('headerMenu_ua', [['title' => 'Меню', 'url' => '/ua/catalog']]);
        GlobalSettings::set('workTime_ua', 'Пн-Пт');

        $seo = $page->seoEntity;
        $seo->setTranslation('title', 'ua', 'SEO заголовок');
        $seo->setTranslation('is_active', 'ua', true);
        $seo->saveQuietly();

        return [$product, $page, $seo];
    }

    private function export(): array
    {
        $this->artisan('i18n:export', ['--source' => 'ua', '--out' => $this->file])->assertExitCode(0);

        return json_decode(file_get_contents($this->file), true);
    }

    private function translate(array $data, callable $fn): void
    {
        foreach ($data['entries'] as &$entry) {
            $entry['translation'] = $fn($entry);
        }
        file_put_contents($this->file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function fakeTranslate(array $entry): mixed
    {
        $v = $entry['value'];
        $wrap = fn ($s) => is_string($s) && !str_starts_with($s, '/') ? 'XX:' . $s : $s;

        return is_array($v)
            ? array_map(fn ($item) => array_map($wrap, $item), isset($v[0]) ? $v : [$v])
            : $wrap($v);
    }

    public function test_export_format(): void
    {
        [$product, $page] = $this->seedContent();
        $data = $this->export();

        $this->assertSame('ua', $data['source']);
        $kinds = array_column($data['entries'], 'kind');
        $this->assertContains('model', $kinds);
        $this->assertContains('meta', $kinds);
        $this->assertContains('setting', $kinds);

        $this->assertContains(
            ['kind' => 'model', 'model' => Product::class, 'id' => $product->id, 'field' => 'name', 'value' => 'Газовий котел'],
            $data['entries']
        );
        $this->assertContains(
            ['kind' => 'meta', 'table' => 'page_meta', 'owner_type' => Page::class, 'owner_id' => $page->id,
             'key' => 'heroTitle', 'type' => 'string', 'value' => 'Заголовок'],
            $data['entries']
        );
        $cards = collect($data['entries'])->firstWhere('key', 'cards');
        $this->assertSame('collection', $cards['type']);
        $this->assertSame('Картка', $cards['value'][0]['title']);
        $this->assertSame(
            [['title' => 'Меню', 'url' => '/ua/catalog']],
            collect($data['entries'])->firstWhere('key', 'headerMenu')['value']
        );
        $this->assertSame('Пн-Пт', collect($data['entries'])->firstWhere('key', 'workTime')['value']);

        // Non-text and empty values are not exported
        $this->assertEmpty(collect($data['entries'])->whereIn('field', ['og_image', 'is_active']));
        $this->assertEmpty(collect($data['entries'])->where('field', 'price_label'));

        // Deterministic
        $this->assertSame($data['entries'], $this->export()['entries']);
    }

    public function test_round_trip(): void
    {
        [$product, $page, $seo] = $this->seedContent();
        $this->translate($this->export(), fn ($e) => $this->fakeTranslate($e));

        $this->artisan('i18n:import', ['file' => $this->file, '--target' => 'xx'])->assertExitCode(0);

        // model field + link rewriting
        $product->refresh();
        $this->assertSame('XX:Газовий котел', $product->getTranslation('name', 'xx'));
        $this->assertSame(
            'XX:<a href="/xx/catalog">Каталог</a> https://gutgas.eu/xx/about',
            $product->getTranslation('description', 'xx')
        );
        $this->assertSame('Газовий котел', $product->getTranslation('name', 'ua'));

        // string meta (same type), collection meta (json, rewritten links)
        $row = DB::table('page_meta')->where('key', 'heroTitle_xx')->first();
        $this->assertSame('XX:Заголовок', $row->value);
        $this->assertSame('string', $row->type);
        $this->assertSame($page->id, (int) $row->owner_id);
        $cards = DB::table('page_meta')->where('key', 'cards_xx')->first();
        $this->assertSame('collection', $cards->type);
        $this->assertSame(
            [['title' => 'XX:Картка', 'html' => 'XX:<a href="/xx/x">x</a>']],
            json_decode($cards->value, true)
        );

        // settings: same storage format as the source row
        $this->assertSame(
            [['title' => 'XX:Меню', 'url' => '/xx/catalog']],
            json_decode(GlobalSettings::get('headerMenu_xx'), true)
        );
        $this->assertSame('XX:Пн-Пт', GlobalSettings::get('workTime_xx'));

        // is_active copied
        $this->assertTrue($seo->fresh()->getTranslations('is_active')['xx']);
    }

    public function test_existing_target_is_skipped_unless_forced(): void
    {
        [, $page] = $this->seedContent();
        $this->translate($this->export(), fn ($e) => $this->fakeTranslate($e));

        $this->artisan('i18n:import', ['file' => $this->file, '--target' => 'xx'])->assertExitCode(0);
        $this->assertSame('Already translated', $page->fresh()->getTranslation('title', 'xx'));

        $this->artisan('i18n:import', ['file' => $this->file, '--target' => 'xx', '--force' => true])->assertExitCode(0);
        $this->assertSame('XX:Головна', $page->fresh()->getTranslation('title', 'xx'));
    }

    public function test_changed_source_is_skipped_and_dry_run_writes_nothing(): void
    {
        [$product] = $this->seedContent();
        $this->translate($this->export(), fn ($e) => $this->fakeTranslate($e));

        $product->setTranslation('name', 'ua', 'Змінена назва')->saveQuietly();
        GlobalSettings::set('workTime_ua', 'Змінено');

        $this->artisan('i18n:import', ['file' => $this->file, '--target' => 'xx', '--dry-run' => true])
            ->assertExitCode(0);
        $this->assertNull(GlobalSettings::get('headerMenu_xx'));
        $this->assertDatabaseMissing('page_meta', ['key' => 'heroTitle_xx']);

        $this->artisan('i18n:import', ['file' => $this->file, '--target' => 'xx'])
            ->expectsOutputToContain('model ' . Product::class . '#' . $product->id . '.name')
            ->assertExitCode(0);

        $this->assertArrayNotHasKey('xx', $product->fresh()->getTranslations('name'));
        $this->assertNull(GlobalSettings::get('workTime_xx'));
        // untouched entries still imported
        $this->assertNotNull(GlobalSettings::get('headerMenu_xx'));
    }

    public function test_entries_without_translation_are_skipped(): void
    {
        $this->seedContent();
        $data = $this->export();
        file_put_contents($this->file, json_encode($data));

        $this->artisan('i18n:import', ['file' => $this->file, '--target' => 'xx'])->assertExitCode(0);

        $this->assertNull(GlobalSettings::get('workTime_xx'));
        $this->assertDatabaseMissing('page_meta', ['key' => 'heroTitle_xx']);
    }
}
