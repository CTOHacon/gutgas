<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'email' => ['required', 'string', 'email'],
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $identifier = $this->input('identifier');
        $password = $this->input('password');
        Log::channel('auth')->info('Auth attempt started', [
            'identifier' => $identifier,
            'ip' => $this->ip(),
            'remember' => $this->boolean('remember'),
            'throttle_key' => $this->throttleKey(),
            'attempts' => RateLimiter::attempts($this->throttleKey()),
        ]);
        if (
            !Auth::attempt([
                'email' => $identifier,
                'password' => $password
            ], $this->boolean('remember'))
            &&
            !Auth::attempt([
                'username' => $identifier,
                'password' => $password
            ], $this->boolean('remember'))
        ) {
            // Record a failed attempt with a 10 minute decay (600 seconds)
            RateLimiter::hit($this->throttleKey(), 600);
            Log::channel('auth')->warning('Auth attempt failed', [
                'identifier' => $identifier,
                'ip' => $this->ip(),
                'attempts_after_hit' => RateLimiter::attempts($this->throttleKey()),
                'max_attempts' => 3,
            ]);

            throw ValidationException::withMessages([
                'identifier' => trans('auth.failed')
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Log::channel('auth')->info('Auth attempt successful', [
            'identifier' => $identifier,
            'ip' => $this->ip(),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
    // Allow max 3 attempts within a 10 minute window
    if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        Log::channel('auth')->notice('Auth lockout triggered', [
            'throttle_key' => $this->throttleKey(),
            'identifier' => $this->input('identifier'),
            'ip' => $this->ip(),
            'retry_after_seconds' => $seconds,
            'max_attempts' => 3,
        ]);

        throw ValidationException::withMessages([
            'identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('identifier')) . '|' . $this->ip());
    }
}
