<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateWebhookToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Webhook-Token');
        $secret = env('WA_WEBHOOK_SECRET');

        if (empty($secret) || $token !== $secret) {
            \Illuminate\Support\Facades\Log::warning('Webhook Token Mismatch. Received: ' . ($token ?? 'NULL') . ' expected: ' . ($secret ?? 'NULL'));
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid webhook token.'
            ], 401);
        }

        return $next($request);
    }
}
