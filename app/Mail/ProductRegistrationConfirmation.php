<?php

namespace App\Mail;

use App\Models\ProductRegistration;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Double opt-in email: echoes the submitted data and links to the signed confirmation URL.
 * Send it with ->locale($registration->mailLocale()) so subject and body follow the form's language.
 */
class ProductRegistrationConfirmation extends Mailable
{
    public string $confirmationUrl;

    public function __construct(public ProductRegistration $registration)
    {
        $this->confirmationUrl = $registration->confirmationUrl();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('product-registration.email_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.productRegistrationConfirmation',
            with: [
                'rows' => $this->rows(),
                'url'  => $this->confirmationUrl,
            ],
        );
    }

    /**
     * Submitted fields as localized "label => value" pairs; optional empty fields are skipped.
     *
     * @return array<string, string>
     */
    private function rows(): array
    {
        $r = $this->registration;

        $rows = [
            __('product-registration.label_first_name')     => $r->first_name,
            __('product-registration.label_last_name')      => $r->last_name,
            __('product-registration.label_phone')          => $r->phone,
            __('product-registration.label_email')          => $r->email,
            __('product-registration.label_city')           => $r->city,
            __('product-registration.label_address')        => $r->address,
            __('product-registration.label_model')          => $r->model,
            __('product-registration.label_serial_number')  => $r->serial_number,
            __('product-registration.label_purchase_date')  => $r->purchase_date?->format('d.m.Y'),
            __('product-registration.label_price')          => number_format((int) $r->price, 0, '', ' '),
            __('product-registration.label_purchase_place') => $r->purchase_place,
            __('product-registration.email_label_subscription') => $r->subscription_consent
                ? __('product-registration.email_yes')
                : __('product-registration.email_no'),
        ];

        return array_filter($rows, fn ($value) => $value !== null && $value !== '');
    }
}
