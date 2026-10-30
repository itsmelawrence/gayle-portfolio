<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Verifies a Cloudflare Turnstile token server-side. Skipped when no secret key is configured (local development).
 */
class Turnstile implements ValidationRule
{
    // Run even when the token field is missing entirely (a bot posting directly)
    public bool $implicit = true;

    public function __construct(private ?string $ip = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret_key');

        if (! $secret) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the verification check.');

            return;
        }

        try {
            $result = Http::asForm()->timeout(5)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => $this->ip,
            ])->json();
        } catch (Throwable $e) {
            Log::warning('Turnstile verification request failed', ['error' => $e->getMessage()]);
            $result = null;
        }

        if (! ($result['success'] ?? false)) {
            $fail('The verification check failed. Please try again.');
        }
    }
}
