<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Read-only feed of saved inquiries for the JARVIS Mac app.
 * The app pages forwards with after_id, so each lead is sent once.
 * IP address and user agent stay on the server.
 */
class JarvisLeadController extends Controller
{
    private const PAGE_SIZE = 100;

    public function __invoke(Request $request): JsonResponse
    {
        $token = (string) config('services.jarvis.leads_token');
        // Without a configured token the feed does not exist.
        abort_if($token === '', 404);
        abort_unless(hash_equals($token, (string) $request->bearerToken()), 401);

        $leads = Lead::query()
            ->where('id', '>', max(0, $request->integer('after_id')))
            ->orderBy('id')
            ->limit(self::PAGE_SIZE + 1)
            ->get();

        return response()->json([
            'data' => $leads->take(self::PAGE_SIZE)->map(fn (Lead $lead): array => [
                'id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email,
                'company' => $lead->company,
                'service' => $lead->service,
                'message' => $lead->details,
                'status' => $lead->status,
                'utm_source' => $lead->utm_source,
                'utm_medium' => $lead->utm_medium,
                'utm_campaign' => $lead->utm_campaign,
                'referrer' => $lead->referrer,
                'landing_page' => $lead->landing_page,
                'created_at' => $lead->created_at?->toIso8601ZuluString(),
            ])->values(),
            'has_more' => $leads->count() > self::PAGE_SIZE,
        ]);
    }
}
