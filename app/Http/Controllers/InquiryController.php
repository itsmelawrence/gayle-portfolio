<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\InquiryReceived;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function __invoke(StoreInquiryRequest $request): JsonResponse|RedirectResponse
    {
        $recipient = config('portfolio.contact.email');

        abort_unless($recipient, 503, 'The contact form is not connected yet.');

        Mail::to($recipient)->send(new InquiryReceived($request->validated()));

        return $request->expectsJson()
            ? response()->json(['message' => 'Inquiry sent.'])
            : redirect()->to(route('home').'#contact')->with('inquiry_sent', true);
    }
}
