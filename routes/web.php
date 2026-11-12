<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NovaPoshtaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductRegistrationController;
use App\Http\Controllers\SeoEndpoint;
use App\Http\Controllers\StaticHtmlController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/ajax.php';

Route::post('/mail/consultation', [MailController::class, 'consultationMail'])->middleware('throttle:5,1')->name('consultationMail');
Route::post('/mail/requestCall', [MailController::class, 'requestCallMail'])->middleware('throttle:5,1')->name('requestCallMail');

Route::post('/order/store', [OrderController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('order.store');

Route::prefix('api/nova-poshta')->middleware('throttle:60,1')->as('novaPoshta.')->group(function () {
    Route::get('/settlements', [NovaPoshtaController::class, 'settlements'])->name('settlements');
    Route::get('/warehouses', [NovaPoshtaController::class, 'warehouses'])->name('warehouses');
});

// Product registration (warranty)
Route::post('/registration', [ProductRegistrationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('productRegistration.store');

// Documents (manuals) linked from page content as "https://gutgas.eu/<file>.pdf" live in
// the media library; "/ua/<file>.pdf" covers old 301s cached by browsers.
Route::get('/{path}', function (string $path) {
    $file = basename($path);
    abort_unless(Storage::disk('public')->exists('media/' . $file), 404);

    return redirect('/storage/media/' . rawurlencode($file), 301);
})->where('path', '(?:[a-z]{2}/)?[A-Za-z0-9_\-.]+\.pdf');

Route::prefix('{locale?}')->middleware([
    'seoRedirect',
    'urlLocaleHandler',
    'shareSeoEntityBreadcrumbs',
])->group(function () {
    Route::get('/checkout', CheckoutController::class)->name('checkout');

    Route::get('/registration', [ProductRegistrationController::class, 'create'])->name('productRegistration');
    // Signed link from the confirmation email; the signature is checked in the controller
    // so a broken link gets a friendly page instead of a bare 403.
    Route::get('/registration/confirm/{registration}', [ProductRegistrationController::class, 'confirm'])
        ->whereNumber('registration')
        ->middleware('throttle:20,1')
        ->name('productRegistration.confirm');

    Route::get('/thank-you', function () {
        return Inertia::render('ThankYou')->with('thankYouTranslations', trans('thank-you'));
    })->name('thankYou');

    Route::get('/{route?}/page-{pageNumber?}', SeoEndpoint::class)
        ->where(['route' => '.*', 'pageNumber' => '[0-9]+'])
        ->name('seo-entity-paged');
    Route::get('/{route?}', SeoEndpoint::class)->where('route', '.*')->name('seo-entity');
});

Route::fallback(function () {
    return Inertia::render('404');
});
