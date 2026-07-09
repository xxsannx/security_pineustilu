<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
            if ($booking->user_id) {
                if (!auth()->check() || $booking->user_id !== auth()->id()) {
                    AuditLogService::logUnauthorizedAccess($request->url(), auth()->id());
                    AuditLogService::logIdorAttempt($bookingToken, auth()->id());
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 403);
                }
            }

            // Jika booking milik guest (user_id null)
            if (!$booking->user_id) {
                if (session('verified_detail_token') !== $bookingToken) {
                    AuditLogService::logUnauthorizedAccess($request->url(), auth()->id());
                    AuditLogService::logIdorAttempt($bookingToken, auth()->id());
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 403);
                }
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
            // 1. Validasi struktur payload
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|string',
                'status_code' => 'required|string',
                'gross_amount' => 'required',
                'transaction_status' => 'required|string',
                'fraud_status' => 'required|string',
                'transaction_id' => 'required|string',
                'signature_key' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::warning('Midtrans notification invalid payload structure', [
                    'errors' => $validator->errors()->all(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payload structure',
                ], 400);
            }

            $orderId = $request->input('order_id');
            $statusCode = $request->input('status_code');
            $grossAmount = $request->input('gross_amount');
            $signatureKey = $request->input('signature_key');

            // 2. Validasi signature (fail-closed, constant-time compare)
            $serverKey = config('midtrans.server_key');
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if (!hash_equals($expectedSignature, $signatureKey)) {
                AuditLogService::log(
                    'payment_fraud_attempt',
                    "Terdeteksi percobaan pemalsuan webhook pembayaran (signature mismatch) untuk Order ID: {$orderId}",
                    null,
                    'CRITICAL'
                );
                Log::error('Signature verification failed for order_id: ' . $orderId);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized signature',
                ], 401);
            }

            // 3. Verifikasi ulang ke Midtrans Get Status API
            $statusResponse = $this->midtransService->getPaymentStatus($orderId);
            if (!$statusResponse) {
                Log::error('Failed to verify status from Midtrans API for order_id: ' . $orderId);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to verify payment status with Midtrans API',
                ], 500);
            }

            // 4. Cocokkan gross_amount hasil Get Status API dengan data lokal
            $payment = \App\Models\Payment::where('order_id', $orderId)->first();
            if (!$payment) {
                Log::warning('Payment record not found for order_id: ' . $orderId);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment record not found',
                ], 404);
            }

            $apiGrossAmount = $statusResponse['gross_amount'] ?? null;
            if (!$apiGrossAmount || abs((float)$apiGrossAmount - (float)$payment->gross_amount) > 0.01) {
                Log::warning('Payment webhook amount mismatch', [
                    'order_id' => $orderId,
                    'local_amount' => $payment->gross_amount,
                    'api_amount' => $apiGrossAmount
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount mismatch',
                ], 409);
            }

            // Cek fraud_status jika transaksi adalah capture
            $apiStatus = $statusResponse['transaction_status'] ?? '';
            $apiFraud = $statusResponse['fraud_status'] ?? '';
            if ($apiStatus === 'capture' && $apiFraud !== 'accept') {
                Log::warning('Capture payment not accepted by fraud detection', [
                    'order_id' => $orderId,
                    'fraud_status' => $apiFraud
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment fraud detection failed',
                ], 400);
            }

            // 5. Kirim data yang sudah divalidasi ke MidtransService untuk disimpan secara atomic & idempotent
            $this->midtransService->handleVerifiedNotification($statusResponse);

            return response()->json([
                'success' => true,
                'message' => 'Notification processed successfully',
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

            // Check if user is authorized to view this booking
            if ($booking->user_id) {
                if (!auth()->check() || $booking->user_id !== auth()->id()) {
                    AuditLogService::logUnauthorizedAccess($request->url(), auth()->id());
                    AuditLogService::logIdorAttempt($bookingToken, auth()->id());
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 403);
                }
            }

            // Jika booking milik guest (user_id null)
            if (!$booking->user_id) {
                if (session('verified_detail_token') !== $bookingToken) {
                    AuditLogService::logUnauthorizedAccess($request->url(), auth()->id());
                    AuditLogService::logIdorAttempt($bookingToken, auth()->id());
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 403);
                }
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
