<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\AuditLogService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransService
{
    private string $serverKey;
    private string $clientKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production', false);
        $this->baseUrl = $isProduction
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';
    }

    /**
     * Generate Snap transaction token
     */
    public function generateSnapToken(Booking $booking): ?string
    {
        try {
            // Load relationships if not already loaded
            if (!$booking->relationLoaded('bookingDetails')) {
                $booking->load('bookingDetails');
            }
            if (!$booking->relationLoaded('bookingOutbounds')) {
                $booking->load('bookingOutbounds');
            }

            // Detect reschedule with additional payment required
            $reschedulePayable = $this->getReschedulePayableAmount($booking);

            // Calculate total amount: use payable (selisih) for reschedule, full total otherwise
            $totalAmount = $reschedulePayable !== null
                ? $reschedulePayable
                : $this->calculateBookingTotal($booking);

            if ($totalAmount <= 0) {
                Log::error('Invalid booking amount for booking: ' . $booking->id);
                return null;
            }

            // Prepare transaction details
            $transactionData = $this->prepareTransactionData($booking, $totalAmount, $reschedulePayable !== null);

            // Call Midtrans API
            $response = Http::withBasicAuth($this->serverKey, '')
                ->post($this->baseUrl . '/snap/v1/transactions', $transactionData);

            if (!$response->successful()) {
                Log::error('Midtrans API Error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'booking_id' => $booking->id,
                ]);

                AuditLogService::log(
                    'midtrans_api_failed',
                    "Gagal membuat Snap Token untuk booking ID {$booking->id}. HTTP Status: " . $response->status(),
                    $booking->user_id,
                    'WARNING'
                );

                return null;
            }

            $responseData = $response->json();
            $snapToken = $responseData['token'] ?? null;

            if (!$snapToken) {
                Log::error('No token in Midtrans response', [
                    'response'   => $responseData,
                    'booking_id' => $booking->id,
                ]);
                return null;
            }

            // Save or update payment record with snap token
            $this->savePaymentRecord(
                $booking,
                $responseData,
                $totalAmount,
                $transactionData['transaction_details']['order_id']
            );

            Log::info('Snap token generated successfully', [
                'booking_id'       => $booking->id,
                'order_id'         => $transactionData['transaction_details']['order_id'],
                'is_reschedule'    => $reschedulePayable !== null,
                'charged_amount'   => $totalAmount,
            ]);

            return $snapToken;

        } catch (Exception $e) {
            Log::error('Error generating Snap token: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'exception'  => $e,
            ]);

            AuditLogService::log(
                'midtrans_api_failed',
                "Gagal membuat Snap Token untuk booking ID {$booking->id}. Exception: " . $e->getMessage(),
                $booking->user_id,
                'WARNING'
            );

            return null;
        }
    }

    /**
     * Check if booking is a reschedule that requires additional payment.
     * Returns the payable amount (selisih) if applicable, null otherwise.
     */
    private function getReschedulePayableAmount(Booking $booking): ?int
    {
        $detail = $booking->bookingDetails->first();
        if (!$detail || !$detail->note) {
            return null;
        }

        $note = json_decode($detail->note, true);
        $ri   = $note['reschedule_info'] ?? null;

        if (!$ri || empty($ri['is_reschedule'])) {
            return null;
        }

        if (($ri['payment_status'] ?? '') !== 'payment_required') {
            return null;
        }

        $payable = (float) ($ri['payable_amount'] ?? $ri['price_delta'] ?? 0);

        return $payable > 0 ? (int) $payable : null;
    }

    /**
     * Prepare transaction data for Midtrans API
     */
    private function prepareTransactionData(Booking $booking, int $totalAmount, bool $isReschedulePayment = false): array
    {
        $orderId = $this->generateOrderId($booking);

        // Get booking details for item details
        $itemDetails = $this->buildItemDetails($booking, $isReschedulePayment, $totalAmount);

        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => explode(' ', $booking->guest_name)[0] ?? '',
                'last_name' => implode(' ', array_slice(explode(' ', $booking->guest_name), 1)) ?? '',
                'email' => $booking->guest_email,
                'phone' => $booking->guest_phone,
            ],
            'item_details' => $itemDetails,
            'credit_card' => [
                'secure' => true,
            ],
            'custom_expiry' => [
                'order_time' => now()->toDateTimeString(),
                'expiry_duration' => 24,
                'unit' => 'hours',
            ],
        ];
    }

    /**
     * Build item details from booking.
     * For reschedule payments, a single line item for the price difference is used.
     */
    private function buildItemDetails(Booking $booking, bool $isReschedulePayment = false, int $rescheduleAmount = 0): array
    {
        // For reschedule top-up payments, show a single 'Selisih Reschedule' line item
        if ($isReschedulePayment && $rescheduleAmount > 0) {
            $detail = $booking->bookingDetails->first();
            $note   = $detail && $detail->note ? json_decode($detail->note, true) : [];
            $ri     = $note['reschedule_info'] ?? [];
            $newArea = $ri['new']['area_name'] ?? ($detail?->unit?->area?->name ?? 'Glamping');
            $newUnit = $ri['new']['unit_name'] ?? ($detail?->unit?->name ?? 'Unit');

            return [
                [
                    'id'       => 'reschedule_diff_' . $booking->id,
                    'price'    => $rescheduleAmount,
                    'quantity' => 1,
                    'name'     => 'Selisih Reschedule – ' . $newArea . ' ' . $newUnit,
                ],
            ];
        }

        $items = [];
        $totalPrice = 0;

        // Add accommodation items from booking details
        foreach ($booking->bookingDetails as $detail) {
            $basePrice = (int) $detail->total_price - (int) $detail->total_extra_charge;
            $totalPrice += $basePrice;

            $items[] = [
                'id' => 'unit_' . $detail->unit_id,
                'price' => $basePrice,
                'quantity' => 1,
                'name' => ($detail->unit?->name ?? 'Accommodation') .
                         ' (' . $detail->check_in->format('M d') . ' - ' . $detail->check_out->format('M d') . ')',
            ];

            // Add extra charges if any
            if ($detail->total_extra_charge > 0) {
                $extraCharge = (int) $detail->total_extra_charge;
                $items[] = [
                    'id' => 'extra_' . $detail->id,
                    'price' => $extraCharge,
                    'quantity' => 1,
                    'name' => 'Additional Charges - ' . ($detail->unit?->name ?? 'Item'),
                ];
                $totalPrice += $extraCharge;
            }
        }

        // Add outbound items if any
        foreach ($booking->bookingOutbounds as $outbound) {
            $price = (int) ($outbound->total_price ?? 0);
            if ($price > 0) {
                $totalPrice += $price;
                $items[] = [
                    'id' => 'outbound_' . $outbound->id,
                    'price' => $price,
                    'quantity' => 1,
                    'name' => $outbound->outbound?->name ?? 'Outbound Activity',
                ];
            }
        }

        return $items;
    }

    /**
     * Calculate total amount for booking
     */
    private function calculateBookingTotal(Booking $booking): int
    {
        $total = 0;

        // Sum booking details
        foreach ($booking->bookingDetails as $detail) {
            $total += (int) $detail->total_price;
        }

        // Sum outbound prices
        foreach ($booking->bookingOutbounds as $outbound) {
            $total += (int) ($outbound->total_price ?? 0);
        }

        return $total;
    }

    /**
     * Generate unique order ID for transaction
     */
    private function generateOrderId(Booking $booking): string
    {
        $bookingType = strtoupper(substr($booking->booking_type, 0, 3)); // GLM, OUT
        $timestamp = now()->format('YmdHis');
        $bookingId = str_pad($booking->id, 6, '0', STR_PAD_LEFT);

        return "{$bookingType}-{$bookingId}-{$timestamp}";
    }

    /**
     * Save payment record with Snap token
     */
    private function savePaymentRecord(Booking $booking, array $responseData, int $totalAmount, string $orderId): void
    {
        $payment = Payment::where('booking_id', $booking->id)->first();

        $paymentData = [
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'snaptoken' => $responseData['token'] ?? null,
            'gross_amount' => $totalAmount,
            'transaction_status' => 'pending',
            'payment_type' => 'snap',
            'expired_at' => now()->addHours(24),
        ];

        if ($payment) {
            $payment->update($paymentData);
        } else {
            Payment::create($paymentData);
        }
    }

    /**
     * Handle verified payment notification from Midtrans
     */
    public function handleVerifiedNotification(array $notificationData): void
    {
        try {
            $orderId = $notificationData['order_id'] ?? null;
            $transactionStatus = $notificationData['transaction_status'] ?? null;
            $transactionId = $notificationData['transaction_id'] ?? null;

            if (!$orderId) {
                Log::warning('Midtrans notification without order_id', $notificationData);
                return;
            }

            // Log payment webhook received
            AuditLogService::logPaymentWebhookReceived($orderId, $transactionStatus ?? 'unknown');

            DB::transaction(function () use ($orderId, $transactionStatus, $transactionId, $notificationData) {
                $payment = Payment::where('order_id', $orderId)->lockForUpdate()->first();
                if (!$payment) {
                    Log::warning('Payment not found for order_id: ' . $orderId);
                    return;
                }

                $booking = Booking::where('id', $payment->booking_id)->lockForUpdate()->first();
                if (!$booking) {
                    Log::warning('Booking not found for payment: ' . $payment->id);
                    return;
                }

                // Cek idempotensi
                if ($booking->status->value === 'berhasil' && in_array($transactionStatus, ['settlement', 'capture'])) {
                    Log::info('Replay ignored: Booking already marked as berhasil', ['order_id' => $orderId]);
                    return;
                }
                if ($booking->status->value === 'dibatalkan' && in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    Log::info('Replay ignored: Booking already marked as dibatalkan', ['order_id' => $orderId]);
                    return;
                }

                // Update payment status
                $this->updatePaymentStatus($payment, $transactionStatus, $transactionId, $notificationData);

                // Update booking status berdasarkan status pembayaran
                if (in_array($transactionStatus, ['settlement', 'capture'])) {
                    $booking->update(['status' => 'berhasil']);
                    // Log payment success
                    AuditLogService::logPaymentSuccess($orderId, (float) $payment->gross_amount, $booking->user_id);
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $booking->update(['status' => 'dibatalkan']);
                    // Log payment failed
                    AuditLogService::logPaymentFailed($orderId, $transactionStatus, $booking->user_id);
                }

                Log::info('Payment notification processed and updated in transaction', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus,
                    'booking_id' => $booking->id,
                ]);
            });

        } catch (Exception $e) {
            Log::error('Error handling Midtrans notification: ' . $e->getMessage(), [
                'exception' => $e,
                'notification' => $notificationData,
            ]);
        }
    }

    /**
     * Update payment status from notification
     */
    private function updatePaymentStatus(Payment $payment, string $status, ?string $transactionId, array $fullData): void
    {
        $updateData = [
            'transaction_status' => $status,
            'transaction_id' => $transactionId,
            'fraud_status' => $fullData['fraud_status'] ?? null,
            'payment_type' => $fullData['payment_type'] ?? null,
        ];

        // Save payment method specific data
        if ($fullData['payment_type'] === 'bank_transfer') {
            $updateData['bank'] = $fullData['bank'] ?? null;
            $updateData['va_number'] = $fullData['va_number'] ?? null;
        } elseif ($fullData['payment_type'] === 'gopay') {
            $updateData['qr_string'] = $fullData['gopay_qr_string'] ?? null;
            $updateData['deeplink_url'] = $fullData['deeplink_url'] ?? null;
        }

        $payment->update($updateData);
    }

    /**
     * Get payment status from Midtrans
     */
    public function getPaymentStatus(string $orderId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->get($this->baseUrl . '/v2/' . $orderId . '/status');

            if (!$response->successful()) {
                Log::error('Error getting payment status from Midtrans', [
                    'order_id' => $orderId,
                    'status' => $response->status(),
                ]);

                AuditLogService::log(
                    'midtrans_api_failed',
                    "Gagal mengambil status pembayaran order {$orderId} dari Midtrans. HTTP Status: " . $response->status(),
                    null,
                    'WARNING'
                );

                return null;
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('Error getting payment status: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'exception' => $e,
            ]);

            AuditLogService::log(
                'midtrans_api_failed',
                "Gagal mengambil status pembayaran order {$orderId} dari Midtrans. Exception: " . $e->getMessage(),
                null,
                'WARNING'
            );

            return null;
        }
    }

    /**
     * Get Snap JS URL
     */
    public function getSnapJsUrl(): string
    {
        $isProduction = config('midtrans.is_production', false);
        return $isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Get Client Key for frontend
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }
}
