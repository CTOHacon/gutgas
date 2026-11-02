<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class ProductRegistration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'purchase_date'        => 'date:Y-m-d',
        'price'                => 'integer',
        'subscription_consent' => 'boolean',
        'confirmed_at'         => 'datetime',
    ];

    public function isConfirmed(): bool
    {
        return $this->confirmed_at !== null;
    }

    /**
     * Locale the customer used for the form, falling back to the default if it is no longer enabled.
     */
    public function mailLocale(): string
    {
        return $this->locale && array_key_exists($this->locale, config('app.locales'))
            ? $this->locale
            : config('app.fallback_locale');
    }

    /**
     * Signed link from the confirmation email. It does not expire: confirming only flips
     * confirmed_at on this one record, the HMAC (APP_KEY) cannot be forged, and customers
     * often open such emails days later.
     *
     * The signature covers only path + query (relative), so an http/https or host mismatch
     * behind a proxy cannot invalidate the link. Check it with hasValidRelativeSignature().
     */
    public function confirmationUrl(): string
    {
        return url(URL::signedRoute('productRegistration.confirm', [
            'locale'       => $this->mailLocale(),
            'registration' => $this->id,
        ], null, false));
    }
}
