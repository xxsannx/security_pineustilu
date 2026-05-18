<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class VerifyMidtransNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the signature key from Midtrans
        $signature = $request->header('X-Midtrans-Signature');

        if (!$signature) {
            return response()->json(['error' => 'Missing signature'], 401);
        }

        // Create signature hash
        $serverKey = config('midtrans.server_key');
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');

        // Build the notification signature string according to Midtrans specification
        $notificationString = $orderId . $statusCode . $grossAmount . $serverKey;
        $hash = hash('sha512', $notificationString);

        // Verify signature matches
        if ($hash !== $signature) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}
