<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Console\Command;

class TestMidtransIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:test {booking_id? : The booking ID to test with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Midtrans integration and token generation';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $bookingId = $this->argument('booking_id');

        $this->info('🔍 Testing Midtrans Integration...');
        $this->newLine();

        // Check configuration
        $this->info('📋 Checking Configuration:');
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');

        if (!$serverKey) {
            $this->error('❌ MIDTRANS_SERVER_KEY is not set in .env');
            return 1;
        }

        if (!$clientKey) {
            $this->error('❌ MIDTRANS_CLIENT_KEY is not set in .env');
            return 1;
        }

        $this->line('✓ Server Key configured');
        $this->line('✓ Client Key configured');
        $this->line('✓ Mode: ' . ($isProduction ? 'Production' : 'Sandbox'));
        $this->newLine();

        // Test with booking
        $this->info('📦 Testing Token Generation:');

        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if (!$booking) {
                $this->error("❌ Booking with ID {$bookingId} not found");
                return 1;
            }
        } else {
            $booking = Booking::latest()->first();
            if (!$booking) {
                $this->error('❌ No bookings found in database');
                return 1;
            }
            $this->line("Using latest booking: #{$booking->id}");
        }

        $this->line("Booking Details:");
        $this->line("  - ID: {$booking->id}");
        $this->line("  - Token: {$booking->token_code}");
        $this->line("  - Guest: {$booking->guest_name}");
        $this->line("  - Email: {$booking->guest_email}");
        $this->newLine();

        // Check booking details
        $bookingDetails = $booking->bookingDetails;
        if ($bookingDetails->isEmpty()) {
            $this->error('❌ Booking has no details (accommodations)');
            return 1;
        }

        $this->line("Booking Details Count: {$bookingDetails->count()}");
        $totalAmount = 0;
        foreach ($bookingDetails as $detail) {
            $amount = (int) $detail->total_price + (int) $detail->total_extra_charge;
            $totalAmount += $amount;
            $this->line("  - {$detail->unit->name}: Rp " . number_format($amount, 0, ',', '.'));
        }

        $this->line("Total Amount: Rp " . number_format($totalAmount, 0, ',', '.'));
        $this->newLine();

        // Generate Snap Token
        $this->info('🔐 Generating Snap Token...');

        $midtransService = app(MidtransService::class);
        $snapToken = $midtransService->generateSnapToken($booking);

        if (!$snapToken) {
            $this->error('❌ Failed to generate Snap token');
            $this->error('Check logs at: storage/logs/laravel.log');
            return 1;
        }

        $this->line('✓ Token generated successfully!');
        $this->newLine();

        // Display token info
        $this->info('🎫 Snap Token Information:');
        $this->line("Token: " . substr($snapToken, 0, 20) . '...');
        $this->line("Full token length: " . strlen($snapToken) . ' characters');
        $this->line("Snap JS URL: " . $midtransService->getSnapJsUrl());
        $this->newLine();

        // Next steps
        $this->info('✅ Test successful!');
        $this->newLine();
        $this->info('📝 Next Steps:');
        $this->line('1. Visit the booking detail page:');
        $this->line("   http://localhost:8000/reservasi/detail-pesanan/{$booking->token_code}");
        $this->line('2. Update booking status to "pembayaran" (payment)');
        $this->line('3. Click "Pay Now" button');
        $this->line('4. Use test card: 4811 1111 1111 1114');
        $this->line('5. CVV: 123, Exp: any future date');
        $this->newLine();

        return 0;
    }
}
