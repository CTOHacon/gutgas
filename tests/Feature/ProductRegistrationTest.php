<?php

namespace Tests\Feature;

use App\Mail\ProductRegistrationConfirmation;
use App\Models\GlobalSettings;
use App\Models\ProductRegistration;
use App\Models\User;
use App\Services\TelegramNotifier;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class ProductRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id'   => '12345',
        ]);

        // Never let a test talk to a real mail server.
        Mail::fake();
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'first_name'           => 'Іван',
            'last_name'            => 'Петренко',
            'phone'                => '+38 (067) 123-45-67',
            'email'                => 'ivan@example.com',
            'city'                 => 'Київ',
            'address'              => 'вул. Хрещатик, 1',
            'model'                => 'GG-500',
            'serial_number'        => 'SN123456',
            'purchase_date'        => now()->subDays(3)->format('Y-m-d'),
            'price'                => 25000,
            'purchase_place'       => 'Магазин, Київ',
            'subscription_consent' => true,
            'locale'               => 'ua',
            'website'              => '',
        ], $overrides);
    }

    public static function intlPhoneProvider(): array
    {
        return [
            'georgia'    => ['+995 555 12 34 56', 'ka'],
            'moldova'    => ['+373 (69) 123-456', 'ro'],
            'kazakhstan' => ['+7 701 123-45-67', 'kk'],
            'eu'         => ['+49 30 1234567', 'en'],
        ];
    }

    /** @dataProvider intlPhoneProvider */
    public function test_international_phone_is_accepted_on_international_locales(string $phone, string $locale): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $this->post(route('productRegistration.store'), $this->validData(['phone' => $phone, 'locale' => $locale]))
            ->assertSessionDoesntHaveErrors('phone');
        $this->assertDatabaseHas('product_registrations', ['phone' => $phone]);
    }

    /** @dataProvider intlPhoneProvider */
    public function test_international_phone_is_rejected_on_ua_and_ru(string $phone): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        foreach (['ua', 'ru'] as $locale) {
            $this->post(route('productRegistration.store'), $this->validData(['phone' => $phone, 'locale' => $locale]))
                ->assertSessionHasErrors('phone');
        }
        $this->assertDatabaseCount('product_registrations', 0);
    }

    public function test_garbage_phone_is_rejected_on_every_locale(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        foreach (array_keys(config('app.locales')) as $locale) {
            foreach (['123', 'abc', '+0123456789012', '995555123456'] as $phone) {
                $this->post(route('productRegistration.store'), $this->validData(['phone' => $phone, 'locale' => $locale]))
                    ->assertSessionHasErrors('phone');
            }
        }
    }

    public function test_valid_submission_is_stored_and_sent_to_telegram(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $response = $this->post(route('productRegistration.store'), $this->validData([
            'first_name' => 'Іван <b>&',
        ]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('product_registrations', [
            'last_name'            => 'Петренко',
            'serial_number'        => 'SN123456',
            'price'                => 25000,
            'subscription_consent' => 1,
        ]);

        Http::assertSent(function (Request $request) {
            return str_starts_with($request->url(), 'https://api.telegram.org/bottest-token/sendMessage')
                && $request['chat_id'] === '12345'
                && $request['parse_mode'] === 'HTML'
                && str_contains($request['text'], '🛡 Реєстрація продукту #')
                && str_contains($request['text'], 'Іван &lt;b&gt;&amp;')
                && !str_contains($request['text'], 'Іван <b>')
                && str_contains($request['text'], 'SN123456');
        });
    }

    public function test_invalid_input_is_rejected(): void
    {
        Http::fake();

        $response = $this->post(route('productRegistration.store'), $this->validData([
            'first_name'    => '',
            'phone'         => '123',
            'email'         => 'not-an-email',
            'serial_number' => '',
            'purchase_date' => now()->addDays(2)->format('Y-m-d'),
            'price'         => -5,
        ]));

        $response->assertSessionHasErrors([
            'first_name', 'phone', 'email', 'serial_number', 'purchase_date', 'price',
        ]);
        $this->assertDatabaseCount('product_registrations', 0);
        Http::assertNothingSent();
    }

    public function test_honeypot_submission_is_not_stored(): void
    {
        Http::fake();

        $response = $this->post(route('productRegistration.store'), $this->validData(['website' => 'http://spam.example']));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 0);
        Http::assertNothingSent();
    }

    public function test_honeypot_with_invalid_data_fails_silently(): void
    {
        Http::fake();

        $response = $this->post(route('productRegistration.store'), ['website' => 'spam']);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 0);
    }

    public function test_telegram_failure_does_not_break_submission(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => false], 500)]);

        $response = $this->post(route('productRegistration.store'), $this->validData());

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 1);
    }

    public function test_telegram_connection_exception_does_not_break_submission(): void
    {
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('timeout'));

        $response = $this->post(route('productRegistration.store'), $this->validData());

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 1);
        $this->assertFalse(app(TelegramNotifier::class)->send('x'));
    }

    public function test_public_page_is_available(): void
    {
        $this->withoutVite()->get("/ua/registration")->assertOk();
    }

    public function test_menu_migration_adds_link_once(): void
    {
        GlobalSettings::set('headerMenu_ua', [['title' => 'Головна', 'url' => '/']]);

        $migration = require database_path('migrations/2025_08_20_100100_add_product_registration_permissions_and_menu.php');
        $migration->up();
        $migration->up();

        $items = json_decode(GlobalSettings::get('headerMenu_ua'), true);
        $this->assertCount(2, $items);
        $this->assertSame('/ua/product-registration', $items[1]['url']);
    }

    public function test_menu_url_migration_switches_to_registration(): void
    {
        GlobalSettings::set('headerMenu_ua', [
            ['title' => 'Головна', 'url' => '/'],
            ['title' => 'Реєстрація продукту', 'url' => '/ua/product-registration'],
        ]);

        $migration = require database_path('migrations/2026_10_30_120000_rename_product_registration_menu_url.php');
        $migration->up();

        $items = json_decode(GlobalSettings::get('headerMenu_ua'), true);
        $this->assertSame('/', $items[0]['url']);
        $this->assertSame('/ua/registration', $items[1]['url']);
    }

    public function test_admin_list_requires_authentication(): void
    {
        ProductRegistration::create([
            'first_name' => 'A', 'last_name' => 'B', 'phone' => '+38 (067) 123-45-67', 'email' => 'a@b.com',
            'city' => 'Київ', 'serial_number' => 'S1', 'purchase_date' => '2025-01-01', 'price' => 1,
            'purchase_place' => 'X',
        ]);

        $this->get(route('admin.productRegistration.index'))->assertRedirect();
        $this->get(route('admin.productRegistration.show', 1))->assertRedirect();
        $this->delete(route('admin.productRegistration.destroy', 1))->assertRedirect();
        $this->assertDatabaseCount('product_registrations', 1);
    }

    private function registration(array $overrides = []): ProductRegistration
    {
        return ProductRegistration::create(array_merge([
            'first_name' => 'Іван', 'last_name' => 'Петренко', 'phone' => '+38 (067) 123-45-67',
            'email' => 'ivan@example.com', 'city' => 'Київ', 'serial_number' => 'SN123456',
            'purchase_date' => '2025-01-01', 'price' => 25000, 'purchase_place' => 'Магазин, Київ',
            'locale' => 'ua',
        ], $overrides));
    }

    /**
     * Only Telegram calls: page renders may also POST to the Inertia SSR server when a bundle is built.
     */
    private function telegramRequestCount(): int
    {
        return Http::recorded(fn (Request $request) => str_contains($request->url(), 'api.telegram.org'))->count();
    }

    private function sentConfirmation(): ProductRegistrationConfirmation
    {
        $sent = Mail::sent(ProductRegistrationConfirmation::class);
        $this->assertCount(1, $sent);

        return $sent->first();
    }

    public function test_confirmation_email_is_sent_in_submission_locale_with_signed_link(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $this->post(route('productRegistration.store'), $this->validData([
            'locale' => 'ru',
            'model'  => 'GHCL1222',
        ]))->assertSessionHasNoErrors();

        $registration = ProductRegistration::firstOrFail();
        $this->assertNull($registration->confirmed_at);
        $this->assertSame('ru', $registration->locale);

        $mail = $this->sentConfirmation();
        $this->assertTrue($mail->hasTo('ivan@example.com'));
        $this->assertSame('ru', $mail->locale);

        $mail->assertHasSubject('Подтвердите регистрацию продукта GUTGAS');
        $mail->assertSeeInHtml('Поздравляем с покупкой и благодарим, что выбрали GUTGAS!');
        $mail->assertSeeInHtml('ПОДТВЕРДИТЬ');
        // Every submitted field with its localized label
        foreach (['Имя', 'Фамилия', 'Телефон', 'E-mail', 'Город', 'Адрес (улица, дом)', 'Модель или артикул',
                  'Серийный номер', 'Дата покупки', 'Стоимость покупки, грн', 'Где купили', 'Подписка на новости'] as $label) {
            $mail->assertSeeInHtml($label);
        }
        foreach (['Петренко', '+38 (067) 123-45-67', 'ivan@example.com', 'Київ', 'вул. Хрещатик, 1', 'GHCL1222',
                  'SN123456', now()->subDays(3)->format('d.m.Y'), '25 000', 'Магазин, Київ', 'Да'] as $value) {
            $mail->assertSeeInHtml($value);
        }
        $mail->assertDontSeeInHtml('Підтвердьте');

        // The link points to the localized confirm page and carries a signature
        $this->assertStringContainsString("/ru/registration/confirm/{$registration->id}?signature=", $mail->confirmationUrl);
        $mail->assertSeeInHtml($mail->confirmationUrl);
    }

    public function test_confirmation_email_defaults_to_ukrainian(): void
    {
        Http::fake();

        $this->post(route('productRegistration.store'), $this->validData(['locale' => null]))
            ->assertSessionHasNoErrors();

        $mail = $this->sentConfirmation();
        $this->assertSame('ua', $mail->locale);
        $mail->assertHasSubject('Підтвердьте реєстрацію продукту GUTGAS');
        $mail->assertSeeInHtml('Вітаємо з покупкою і дякуємо, що обрали GUTGAS!');
        $mail->assertSeeInHtml('ПІДТВЕРДИТИ');
    }

    public function test_link_from_email_confirms_registration(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $this->post(route('productRegistration.store'), $this->validData())->assertSessionHasNoErrors();
        $registration = ProductRegistration::firstOrFail();
        $url = $this->sentConfirmation()->confirmationUrl;

        $this->withoutVite()->get($url)
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('ProductRegistrationConfirm')
                ->where('confirmed', true)
                ->where('registrationTranslations.confirm_success_title', 'Реєстрацію підтверджено'));

        $this->assertNotNull($registration->fresh()->confirmed_at);

        Http::assertSent(fn (Request $request) => str_contains($request['text'], "✅ Реєстрацію #{$registration->id} підтверджено")
            && str_contains($request['text'], route('admin.productRegistration.show', $registration->id))
            && str_contains($request['text'], 'SN123456'));
    }

    public function test_second_confirmation_is_idempotent(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);
        $registration = $this->registration();
        $url = $registration->confirmationUrl();

        $this->travelTo(now()->subHour());
        $this->withoutVite()->get($url)->assertOk();
        $firstConfirmedAt = $registration->fresh()->confirmed_at;
        $this->travelBack();

        $this->withoutVite()->get($url)
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('confirmed', true));

        $this->assertNotNull($firstConfirmedAt);
        $this->assertEquals($firstConfirmedAt, $registration->fresh()->confirmed_at);
        // Telegram is told about the confirmation only once
        $this->assertSame(1, $this->telegramRequestCount());
    }

    public function test_tampered_or_missing_signature_shows_friendly_page(): void
    {
        Http::fake();
        $registration = $this->registration(['locale' => 'ru']);
        $other = $this->registration(['serial_number' => 'SN2']);
        $url = $registration->confirmationUrl();

        $broken = [
            'bad signature' => preg_replace('/signature=([0-9a-f])/', 'signature=x$1', $url),
            'no signature'  => strtok($url, '?'),
            'other record'  => str_replace("/confirm/{$registration->id}?", "/confirm/{$other->id}?", $url),
        ];

        foreach ($broken as $case => $brokenUrl) {
            $this->assertNotSame($url, $brokenUrl, $case);
            $this->withoutVite()->get($brokenUrl)
                ->assertForbidden()
                ->assertInertia(fn (Assert $page) => $page
                    ->component('ProductRegistrationConfirm')
                    ->where('confirmed', false)
                    ->where('registrationTranslations.confirm_error_title', 'Ссылка недействительна'));
        }

        $this->assertNull($registration->fresh()->confirmed_at);
        $this->assertNull($other->fresh()->confirmed_at);
        $this->assertSame(0, $this->telegramRequestCount());
    }

    public function test_link_for_deleted_registration_shows_friendly_page(): void
    {
        Http::fake();
        $registration = $this->registration();
        $url = $registration->confirmationUrl();
        $registration->delete();

        $this->withoutVite()->get($url)
            ->assertForbidden()
            ->assertInertia(fn (Assert $page) => $page->where('confirmed', false));
    }

    public function test_honeypot_submission_sends_no_email(): void
    {
        Http::fake();

        $this->post(route('productRegistration.store'), $this->validData(['website' => 'http://spam.example']));

        Mail::assertNothingOutgoing();
    }

    public function test_invalid_submission_sends_no_email(): void
    {
        Http::fake();

        $this->post(route('productRegistration.store'), $this->validData(['email' => 'nope']))
            ->assertSessionHasErrors('email');

        Mail::assertNothingOutgoing();
    }

    public function test_mail_transport_failure_does_not_break_submission(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);
        Mail::shouldReceive('to')->andThrow(new TransportException('SMTP is down'));
        Log::spy();

        $response = $this->post(route('productRegistration.store'), $this->validData());

        $response->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseCount('product_registrations', 1);
        Log::shouldHaveReceived('error')
            ->withArgs(fn ($message, $context = []) => str_contains($message, 'confirmation email')
                && ($context['error'] ?? null) === 'SMTP is down')
            ->once();
    }

    public function test_submit_telegram_message_mentions_pending_confirmation(): void
    {
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $this->post(route('productRegistration.store'), $this->validData())->assertSessionHasNoErrors();

        Http::assertSent(fn (Request $request) => str_contains($request['text'], '🛡 Реєстрація продукту #')
            && str_contains($request['text'], '<b>Статус:</b> очікує підтвердження email'));
    }

    public function test_confirm_route_is_throttled(): void
    {
        Http::fake();
        $url = $this->registration()->confirmationUrl();

        for ($i = 0; $i < 20; $i++) {
            $this->withoutVite()->get($url)->assertOk();
        }
        $this->withoutVite()->get($url)->assertStatus(429);
    }

    public function test_admin_can_resend_confirmation_email(): void
    {
        $registration = $this->registration(['locale' => 'ka']);

        $this->post(route('admin.productRegistration.resendConfirmation', $registration->id))->assertRedirect();
        Mail::assertNothingOutgoing();

        $this->seed(RoleAndPermissionSeeder::class);
        $this->actingAs(User::factory()->create())
            ->post(route('admin.productRegistration.resendConfirmation', $registration->id))
            ->assertForbidden();
        Mail::assertNothingOutgoing();

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin)
            ->post(route('admin.productRegistration.resendConfirmation', $registration->id))
            ->assertSessionHas('message.type', 'success');

        $mail = $this->sentConfirmation();
        $this->assertSame('ka', $mail->locale);
        $mail->assertHasSubject('დაადასტურეთ GUTGAS-ის პროდუქტის რეგისტრაცია');
    }

    public function test_admin_resend_is_skipped_for_confirmed_registration(): void
    {
        $registration = $this->registration(['confirmed_at' => now()]);
        $this->seed(RoleAndPermissionSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->post(route('admin.productRegistration.resendConfirmation', $registration->id))
            ->assertSessionHas('message.type', 'warning');

        Mail::assertNothingOutgoing();
    }

    public function test_lang_files_have_same_registration_keys(): void
    {
        $keys = array_keys(require lang_path('ua/product-registration.php'));
        sort($keys);

        foreach (['ru', 'en', 'ka'] as $locale) {
            $other = array_keys(require lang_path("{$locale}/product-registration.php"));
            sort($other);
            $this->assertSame($keys, $other, "lang/{$locale}/product-registration.php keys differ from ua");
        }
    }
}
