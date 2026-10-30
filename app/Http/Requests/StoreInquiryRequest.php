<?php

namespace App\Http\Requests;

use App\Rules\Turnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function rules(): array
    {
        $services = array_column(config('portfolio.services'), 'title');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'company' => ['nullable', 'string', 'max:160'],
            'service' => ['required', Rule::in([...$services, 'Not sure yet'])],
            'details' => ['required', 'string', 'max:5000'],
            'cf-turnstile-response' => [new Turnstile($this->ip())],
            // Attribution captured by portfolio.js on the first page view of the visit
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:150'],
            'referrer' => ['nullable', 'string', 'max:500'],
            'landing_page' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Bots fill in every field, including the hidden "website" honeypot that people never see.
     */
    public function isSpam(): bool
    {
        return filled($this->input('website'));
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Enter your name.',
            'email.required' => 'Enter an email address, like name@company.com.',
            'email.email' => 'Enter an email address, like name@company.com.',
            'service.required' => 'Choose the service you are interested in.',
            'service.in' => 'Choose the service you are interested in.',
            'details.required' => 'Add a few details about your project.',
        ];
    }
}
