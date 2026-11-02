<?php

namespace App\Jobs;

use App\Mail\ProductRegistrationConfirmation;
use App\Models\ProductRegistration;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Dispatched ->afterResponse() from the public form so the customer never waits on SMTP,
 * and run synchronously from the admin "resend" button. A mail failure is logged, never thrown.
 */
class SendProductRegistrationConfirmation
{
    use Dispatchable, SerializesModels;

    public function __construct(public ProductRegistration $registration)
    {
    }

    public function handle(): bool
    {
        $registration = $this->registration;

        try {
            Mail::to($registration->email, trim($registration->first_name . ' ' . $registration->last_name))
                ->locale($registration->mailLocale())
                ->send(new ProductRegistrationConfirmation($registration));

            return true;
        } catch (Throwable $e) {
            Log::error('Failed to send product registration confirmation email', [
                'registration_id' => $registration->id,
                'email'           => $registration->email,
                'error'           => $e->getMessage(),
            ]);

            return false;
        }
    }
}
