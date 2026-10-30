<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\InquiryReceived;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Throwable;

class InquiryController extends Controller
{
    public function __invoke(StoreInquiryRequest $request): JsonResponse|RedirectResponse
    {
        // Honeypot hits get the normal success response, so bots learn nothing, but nothing is stored or sent
        if (! $request->isSpam()) {
            $this->store($request);
        }

        return $request->expectsJson()
            ? response()->json(['message' => 'Inquiry sent.'])
            : redirect()->to(route('home').'#contact')->with('inquiry_sent', $request->safe()->only(['name', 'email']));
    }

    private function store(StoreInquiryRequest $request): void
    {
        // Save first, so a lead is never lost to a mail outage
        $lead = Lead::create([
            ...$request->safe()->except('cf-turnstile-response'),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        $recipient = config('portfolio.contact.email');

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new InquiryReceived($lead));
            $lead->update(['notified_at' => now()]);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
