<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private readonly MidtransService $midtransService,
    ) {}

    /**
     * Get Snap token for booking
     */
    public function getSnapToken(Request $request, string $bookingToken): JsonResponse
    {
        try {
            $booking = Booking::where('token_code', $bookingToken)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                ], 404);
            }

            // Check if user is authorized to view this booking
            if ($booking->user_id && auth()->id() && $booking->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            // Generate or get existing Snap token
            $snapToken = $this->midtransService->generateSnapToken($booking);

            if (!$snapToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate payment token',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'token' => $snapToken,
                'client_key' => $this->midtransService->getClientKey(),
                'snap_js_url' => $this->midtransService->getSnapJsUrl(),
                'booking_id' => $booking->id,
                'order_id' => $booking->token_code,
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating snap token: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function handleNotification(Request $request): JsonResponse
    {
        try {
            $notification = $request->all();

            // Verify notification signature (recommended for production)
            // You can verify the signature using your server key and the notification body

            $this->midtransService->handleNotification($notification);

            return response()->json([
                'success' => true,
                'message' => 'Notification processed',
            ]);

        } catch (\Exception $e) {
            Log::error('Error handling payment notification: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error processing notification',
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request, string $bookingToken): JsonResponse
    {
        try {
            $booking = Booking::where('token_code', $bookingToken)
                ->with('payments')
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                ], 404);
            }

            // Check if user is authorized
            if ($booking->user_id && auth()->id() && $booking->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $payment = $booking->payments()->latest()->first();

            if (!$payment || !$payment->order_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment record not found',
                ], 404);
            }

            $paymentStatus = $this->midtransService->getPaymentStatus($payment->order_id);

            return response()->json([
                'success' => true,
                'status' => $paymentStatus,
                'booking_status' => $booking->status,
                'payment_status' => $payment->transaction_status,
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting payment status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment status',
            ], 500);
        }
    }
}
