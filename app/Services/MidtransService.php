<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            // Calculate total amount
            $totalAmount = $this->calculateBookingTotal($booking);

            if ($totalAmount <= 0) {
                Log::error('Invalid booking amount for booking: ' . $booking->id);
                return null;
            }

            // Prepare transaction details
            $transactionData = $this->prepareTransactionData($booking, $totalAmount);

            // Call Midtrans API
            $response = Http::withBasicAuth($this->serverKey, '')
                ->post($this->baseUrl . '/snap/v1/transactions', $transactionData);

            if (!$response->successful()) {
                Log::error('Midtrans API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'booking_id' => $booking->id,
                ]);
                return null;
            }

            $responseData = $response->json();
            $snapToken = $responseData['token'] ?? null;

            if (!$snapToken) {
                Log::error('No token in Midtrans response', [
                    'response' => $responseData,
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
                'booking_id' => $booking->id,
                'order_id' => $transactionData['transaction_details']['order_id'],
            ]);

            return $snapToken;

        } catch (Exception $e) {
            Log::error('Error generating Snap token: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'exception' => $e,
            ]);
            return null;
        }
    }

    /**
     * Prepare transaction data for Midtrans API
     */
    private function prepareTransactionData(Booking $booking, int $totalAmount): array
    {
        $orderId = $this->generateOrderId($booking);

        // Get booking details for item details
        $itemDetails = $this->buildItemDetails($booking);

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
     * Build item details from booking
     */
    private function buildItemDetails(Booking $booking): array
    {
        $items = [];
        $totalPrice = 0;

        // Add accommodation items from booking details
        foreach ($booking->bookingDetails as $detail) {
            $price = (int) $detail->total_price;
            $totalPrice += $price;

            $items[] = [
                'id' => 'unit_' . $detail->unit_id,
                'price' => $price,
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
        if ($booking->bookingOutbounds()->count() > 0) {
            foreach ($booking->bookingOutbounds as $outbound) {
                $price = (int) ($outbound->price ?? 0);
                if ($price > 0) {
                    $totalPrice += $price;
                    $items[] = [
                        'id' => 'outbound_' . $outbound->id,
                        'price' => $price,
                        'quantity' => 1,
                        'name' => $outbound->name ?? 'Outbound Activity',
                    ];
                }
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
            $total += (int) $detail->total_extra_charge;
        }

        // Sum outbound prices
        foreach ($booking->bookingOutbounds as $outbound) {
            $total += (int) ($outbound->price ?? 0);
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
     * Handle payment notification from Midtrans
     */
    public function handleNotification(array $notificationData): void
    {
        try {
            $orderId = $notificationData['order_id'] ?? null;
            $transactionStatus = $notificationData['transaction_status'] ?? null;
            $transactionId = $notificationData['transaction_id'] ?? null;

            if (!$orderId) {
                Log::warning('Midtrans notification without order_id', $notificationData);
                return;
            }

            $payment = Payment::where('order_id', $orderId)->first();
            if (!$payment) {
                Log::warning('Payment not found for order_id: ' . $orderId);
                return;
            }

            // Update payment status
            $this->updatePaymentStatus($payment, $transactionStatus, $transactionId, $notificationData);

            // Update booking status based on payment status
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $payment->booking->update(['status' => 'berhasil']);
            } elseif ($transactionStatus === 'deny') {
                $payment->booking->update(['status' => 'booking']);
            }

            Log::info('Payment notification processed', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'booking_id' => $payment->booking_id,
            ]);

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
                return null;
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('Error getting payment status: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'exception' => $e,
            ]);
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
