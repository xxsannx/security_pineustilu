<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingOutbound;
use App\Models\Outbound;
use App\Models\OutboundVariant;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OutboundController extends Controller
{
    /**
     * Display the outbound reservation page with outbounds data.
     */
    public function reservasiOutbound()
    {
        // Get all outbounds with their variants, facilities, galleries, and prices
        $outbounds = Outbound::with([
            'variants.prices',
            'facilities',
            'galleries',
            'prices'
        ])->orderBy('sort_order')
          ->get();

        // Get transportation price (null outbound_id)
        $transportationPrice = Price::whereNull('outbound_id')
            ->whereNull('item_id')
            ->whereNull('unit_id')
            ->first();

        // Prefill contact info for authenticated users
        $authUser = Auth::user();
        $contactPrefill = [
            'is_authenticated' => (bool) $authUser,
            'is_google' => (bool) ($authUser?->google_id),
            'name' => $authUser?->name,
            'email' => $authUser?->email,
            'country_code' => $authUser?->country_code,
            'phone' => $authUser?->phone,
        ];

        return view('reservasi.reservasi-outbound', compact('outbounds', 'transportationPrice', 'contactPrefill'));
    }

    /**
     * Store outbound reservation request.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'outbound_id' => 'required|exists:outbounds,id',
            'variant_id' => 'nullable|exists:outbound_variants,id',
            'activity_date' => 'required|date|after_or_equal:today',
            'guest_count' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'extras' => 'nullable|array',
            'agree' => 'required|accepted',
        ]);

        // Get the outbound
        $outbound = Outbound::with(['variants.prices', 'prices'])->findOrFail($validated['outbound_id']);

        // Get variant if selected
        $variant = null;
        if (!empty($validated['variant_id'])) {
            $variant = OutboundVariant::with('prices')->findOrFail($validated['variant_id']);
        }

        // Calculate price
        $basePrice = 0;
        if ($variant && $variant->prices->count() > 0) {
            $basePrice = $variant->prices->first()->price;
        } elseif ($outbound->prices->count() > 0) {
            $basePrice = $outbound->prices->first()->price;
        }

        $quantity = (int) $validated['guest_count'];
        $subtotal = $basePrice * $quantity;

        // Calculate extras
        $documentationFee = 0;
        $transportationFee = 0;
        $addDocumentation = false;
        $needTransportation = false;

        if (!empty($validated['extras'])) {
            foreach ($validated['extras'] as $extra) {
                if ($extra === 'dokumentasi') {
                    $addDocumentation = true;
                    $documentationFee = 100000; // Dokumentasi price
                } elseif ($extra === 'transportation' || $extra === 'transportasi') {
                    $needTransportation = true;
                    $transportPrice = Price::whereNull('outbound_id')
                        ->whereNull('item_id')
                        ->whereNull('unit_id')
                        ->first();
                    $transportationFee = $transportPrice ? $transportPrice->price : 200000;
                }
            }
        }

        $totalPrice = $subtotal + $documentationFee + $transportationFee;

        // Generate unique token
        $tokenCode = $this->generateUniqueTokenCode();

        // Create booking in transaction
        $booking = DB::transaction(function () use (
            $validated, $outbound, $variant, $basePrice, $quantity, $subtotal,
            $documentationFee, $transportationFee, $addDocumentation, $needTransportation, $totalPrice, $tokenCode
        ) {
            // Create the main booking record
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'token_code' => $tokenCode,
                'guest_name' => $validated['name'],
                'guest_email' => $validated['email'],
                'guest_phone' => $validated['phone'],
                'booking_type' => 'outbound',
                'booking_date' => now()->toDateString(),
                'status' => BookingStatus::PROSES,
            ]);

            // Create the booking outbound detail record
            BookingOutbound::create([
                'booking_id' => $booking->id,
                'outbound_id' => $outbound->id,
                'outbound_variant_id' => $variant?->id,
                'schedule_date' => $validated['activity_date'],
                'total_participants' => $quantity,
                'add_documentation' => $addDocumentation,
                'documentation_fee' => $documentationFee,
                'need_transportation' => $needTransportation,
                'transportation_fee' => $transportationFee,
                'base_price' => $basePrice,
                'subtotal' => $subtotal,
                'total_price' => $totalPrice,
                'note' => json_encode([
                    'outbound_name' => $outbound->name,
                    'variant_name' => $variant?->variant_label,
                    'extras' => $validated['extras'] ?? [],
                ]),
            ]);

            return $booking;
        });

        Log::info('Outbound Booking Created', [
            'booking_id' => $booking->id,
            'token' => $tokenCode,
            'outbound' => $outbound->name,
            'variant' => $variant?->variant_label,
            'date' => $validated['activity_date'],
            'guests' => $quantity,
            'total' => $totalPrice,
        ]);

        // Redirect to order details page
        return redirect()->route('reservasi.detail-pesanan', ['token' => $tokenCode])
            ->with('success', 'Reservasi outbound berhasil dibuat!');
    }

    /**
     * Generate unique token code for booking.
     */
    private function generateUniqueTokenCode(): string
    {
        do {
            $token = strtoupper(Str::random(10));
        } while (Booking::query()->where('token_code', $token)->exists());

        return $token;
    }

    /**
     * Get outbound data via API for JavaScript
     */
    public function getOutboundData($slug)
    {
        $outbound = Outbound::with([
            'variants.prices',
            'facilities',
            'galleries',
            'prices'
        ])->where('slug', $slug)->firstOrFail();

        return response()->json($outbound);
    }

    /**
     * Get all outbounds data via API
     */
    public function getAllOutbounds()
    {
        $outbounds = Outbound::with([
            'variants.prices',
            'facilities',
            'galleries',
            'prices'
        ])->orderBy('sort_order')
          ->get();

        return response()->json($outbounds);
    }
}
