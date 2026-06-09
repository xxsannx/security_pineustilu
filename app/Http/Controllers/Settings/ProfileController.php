<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\Reschedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show profile settings with dynamic tabs.
     */
    public function show(string $tab = 'profile') : \Illuminate\Contracts\View\View
    {
        // Validate tab
        $validTabs = ['profile', 'bookings', 'reschedule', 'cancellation'];
        if (!in_array($tab, $validTabs)) {
            abort(404);
        }

        $data = ['currentTab' => $tab];

        /** @var \App\Models\User $user */
        $user = Auth::user();

        switch ($tab) {
            case 'bookings':
                $bookings = Booking::query()
                    ->where(function ($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('guest_email', $user->email);
                    })
                    ->with([
                        'bookingDetails.unit.area',
                        'bookingOutbounds.outbound',
                        'bookingOutbounds.outboundVariant',
                    ])
                    ->latest('created_at')
                    ->get();

                $data['bookings'] = $bookings->map(fn (Booking $booking) => $this->toBookingCard($booking));
                break;

            case 'reschedule':
                // The new reschedule flow mutates the original Booking and stores reschedule info in BookingDetail's note JSON.
                $reschedules = Booking::query()
                    ->where(function ($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('guest_email', $user->email);
                    })
                    ->whereHas('bookingDetails', function ($q) {
                        $q->where('note', 'LIKE', '%"is_reschedule":true%');
                    })
                    ->with([
                        'bookingDetails.unit.area',
                        'bookingOutbounds.outbound',
                        'bookingOutbounds.outboundVariant',
                    ])
                    ->latest('updated_at') // Reschedules are updates to existing bookings
                    ->get();

                $data['reschedules'] = $reschedules->map(fn (Booking $booking) => $this->toRescheduleCardFromBooking($booking));
                break;

            case 'cancellation':
                $cancellations = Cancellation::query()
                    ->whereHas('booking', fn ($q) => $q->where('user_id', $user->id)->orWhere('guest_email', $user->email))
                    ->with([
                        'booking.bookingDetails.unit.area',
                        'booking.bookingOutbounds.outbound',
                        'booking.bookingOutbounds.outboundVariant',
                    ])
                    ->latest('created_at')
                    ->get();

                $data['cancellations'] = $cancellations->map(fn (Cancellation $cancellation) => $this->toCancellationCard($cancellation));
                break;

            case 'profile':
            default:
                // No additional data needed for profile tab
                break;
        }

        return view('profile', $data);
    }

    private function toBookingCard(Booking $booking) : object
    {
        $glamping = $this->extractGlampingDataFromBooking($booking);
        $outbound = $this->extractOutboundDataFromBooking($booking);

        $base = (object) [
            'id' => $booking->token_code ?: ('BK-' . $booking->id),
            'status' => $booking->status ?? 'pending',
            'guest_name' => (string) ($booking->guest_name ?? ''),
            'guest_phone' => (string) ($booking->guest_phone ?? ''),
            'guest_email' => (string) ($booking->guest_email ?? ''),
            'created_at' => $booking->created_at,
        ];

        if ($outbound !== null) {
            return (object) array_merge((array) $base, $outbound);
        }

        if ($glamping !== null) {
            return (object) array_merge((array) $base, $glamping);
        }

        return (object) array_merge((array) $base, [
            'area' => '-',
            'area_type' => $booking->booking_type ?: 'Booking',
            'check_in' => optional($booking->booking_date)->toDateString(),
            'check_out' => optional($booking->booking_date)->toDateString(),
            'guests' => 0,
            'total' => 0,
            'amenities' => [],
            'extra_charge' => 0.0,
        ]);
    }

    private function toRescheduleCard(Reschedule $reschedule) : object
    {
        $oldBooking = $reschedule->oldBooking;
        $newBooking = $reschedule->newBooking;

        $oldGlamping = $oldBooking ? $this->extractGlampingDataFromBooking($oldBooking) : null;
        $newGlamping = $newBooking ? $this->extractGlampingDataFromBooking($newBooking) : null;

        $oldOutbound = $oldBooking ? $this->extractOutboundDataFromBooking($oldBooking) : null;
        $newOutbound = $newBooking ? $this->extractOutboundDataFromBooking($newBooking) : null;

        // Prefer new booking data for display; fall back to old booking data
        $preferred = $newGlamping ?? $newOutbound ?? $oldGlamping ?? $oldOutbound ?? [];

        $oldCheckIn  = $oldGlamping['check_in']  ?? $oldOutbound['check_in']  ?? null;
        $oldCheckOut = $oldGlamping['check_out'] ?? $oldOutbound['check_out'] ?? null;
        $newCheckIn  = $newGlamping['check_in']  ?? $newOutbound['check_in']  ?? null;
        $newCheckOut = $newGlamping['check_out'] ?? $newOutbound['check_out'] ?? null;

        return (object) array_merge($preferred, [
            'id'              => ($newBooking && $newBooking->token_code) ? $newBooking->token_code : ('RS-' . $reschedule->id),
            'old_check_in'    => $oldCheckIn,
            'old_check_out'   => $oldCheckOut,
            'new_check_in'    => $newCheckIn,
            'new_check_out'   => $newCheckOut,
            'guests'          => (int) ($preferred['guests'] ?? 0),
            'total'           => (float) ($preferred['total'] ?? 0),
            'reschedule_fee'  => (float) ($reschedule->reschedule_fee ?? 0),
            'reason'          => $reschedule->reason,
            'status'          => $reschedule->status ?? 'rescheduled',
            'created_at'      => $reschedule->created_at,
        ]);
    }

    private function toRescheduleCardFromBooking(Booking $booking): object
    {
        $detail = $booking->bookingDetails->first();
        $note = $detail && $detail->note ? json_decode($detail->note, true) : [];
        $ri = $note['reschedule_info'] ?? [];

        // Determine reschedule status based on payment_status or overall booking status
        // Since the reschedule was approved immediately and saved, we can mark it "rescheduled"
        // but if it's pending payment, we could show "pending".
        $status = 'rescheduled';
        if (($ri['payment_status'] ?? '') === 'payment_required' && $booking->status->value === 'proses') {
            $status = 'pending';
        }

        return (object) [
            'id'              => $booking->token_code,
            'status'          => $status,
            'created_at'      => $booking->updated_at, // Update time when reschedule occurred
            'area_type'       => 'Glamping', // Assuming reschedules are glamping only
            
            // New schedule data
            'area'            => $ri['new']['area_name'] ?? ($detail->unit?->area?->name ?? '-'),
            'deck'            => $ri['new']['unit_name'] ?? ($detail->unit?->name ?? '-'),
            'new_check_in'    => $ri['new']['checkin'] ?? ($detail->check_in ? $detail->check_in->toDateString() : null),
            'new_check_out'   => $ri['new']['checkout'] ?? ($detail->check_out ? $detail->check_out->toDateString() : null),
            'total'           => (float) ($ri['new']['total_price'] ?? $detail->total_price ?? 0),
            
            // Old schedule data
            'old_area'        => $ri['original']['area_name'] ?? '-',
            'old_deck'        => $ri['original']['unit_name'] ?? '-',
            'old_check_in'    => $ri['original']['checkin'] ?? null,
            'old_check_out'   => $ri['original']['checkout'] ?? null,
            'old_total'       => (float) ($ri['original']['total_price'] ?? 0),
            
            // Shared data
            'guests'          => (int) ($detail->number_of_people ?? 0),
            'reschedule_fee'  => (float) ($ri['price_delta'] > 0 ? $ri['price_delta'] : 0),
            'reason'          => 'Customer requested reschedule',
        ];
    }

    private function toCancellationCard(Cancellation $cancellation) : object
    {
        $booking  = $cancellation->booking;
        $glamping = $booking ? $this->extractGlampingDataFromBooking($booking) : null;
        $outbound = $booking ? $this->extractOutboundDataFromBooking($booking) : null;

        $preferred = $outbound ?? $glamping ?? [];

        return (object) array_merge($preferred, [
            'id'                => ($booking && $booking->token_code) ? $booking->token_code : ('CN-' . $cancellation->id),
            'guests'            => (int) ($preferred['guests'] ?? 0),
            'total'             => (float) ($preferred['total'] ?? 0),
            'refund'            => (float) ($cancellation->total_refund ?? 0),
            'cancellation_fee'  => (float) ($cancellation->cancellation_fee ?? 0),
            'refund_status'     => $cancellation->refund_status ?? 'pending',
            'status'            => $cancellation->status ?? 'cancelled',
            'cancelled_at'      => optional($cancellation->cancellation_date)->toDateString(),
            'reason'            => $cancellation->reason,
            'created_at'        => $cancellation->created_at,
        ]);
    }

    /**
     * @return array{area:string,area_type:string,deck?:string,check_in:?string,check_out:?string,guests:int,total:float,amenities:array<int,string>,extra_charge:float}|null
     */
    private function extractGlampingDataFromBooking(Booking $booking) : ?array
    {
        $detail = $booking->bookingDetails instanceof Collection ? $booking->bookingDetails->first() : null;
        if (!$detail) {
            return null;
        }

        $areaName = $detail->unit && $detail->unit->area ? $detail->unit->area->name : '-';
        $deckName = $detail->unit ? $detail->unit->name : null;
        $note = $this->decodeNote($detail->note);
        $amenitiesBreakdown = $note['amenities_breakdown'] ?? [];

        $fallbackDate = optional($booking->booking_date)->toDateString();

        $amenities = collect(is_array($amenitiesBreakdown) ? $amenitiesBreakdown : [])
            ->filter(fn ($item) => is_array($item) && ((int) ($item['qty'] ?? 0) > 0))
            ->map(function ($item) {
                $name = (string) ($item['name'] ?? '-');
                $qty = (int) ($item['qty'] ?? 0);

                return $qty > 1 ? ($name . ' (x' . $qty . ')') : $name;
            })
            ->values()
            ->all();

        return [
            'area' => $areaName,
            'area_type' => 'Glamping',
            'deck' => $deckName,
            'check_in' => optional($detail->check_in)->toDateString() ?? $fallbackDate,
            'check_out' => optional($detail->check_out)->toDateString() ?? $fallbackDate,
            'guests' => (int) ($detail->number_of_people ?? 0),
            'total' => (float) ($detail->total_price ?? 0),
            'amenities' => $amenities,
            'extra_charge' => (float) ($detail->total_extra_charge ?? 0),
        ];
    }

    /**
     * @return array{area:string,area_type:string,outbound?:string,variant?:string,check_in:?string,check_out:?string,guests:int,total:float,amenities:array<int,string>,extra_charge:float}|null
     */
    private function extractOutboundDataFromBooking(Booking $booking) : ?array
    {
        $outbound = $booking->bookingOutbounds instanceof Collection ? $booking->bookingOutbounds->first() : null;
        if (!$outbound) {
            return null;
        }

        $outboundName = $outbound->outbound ? $outbound->outbound->name : '-';
        $fallbackDate = optional($booking->booking_date)->toDateString();
        $scheduleDate = optional($outbound->schedule_date)->toDateString() ?? $fallbackDate;
        $variantName = $outbound->outboundVariant?->variant_label;

        $extras = [];
        if ((bool) ($outbound->add_documentation ?? false)) {
            $extras[] = 'Documentation';
        }
        if ((bool) ($outbound->need_transportation ?? false)) {
            $extras[] = 'Transportation';
        }

        return [
            'area' => $outboundName,
            'area_type' => 'Outbound',
            'outbound' => $outboundName,
            'variant' => $variantName,
            'check_in' => $scheduleDate,
            'check_out' => $scheduleDate,
            'guests' => (int) ($outbound->total_participants ?? 0),
            'total' => (float) ($outbound->total_price ?? 0),
            'amenities' => $extras,
            'extra_charge' => (float) (($outbound->documentation_fee ?? 0) + ($outbound->transportation_fee ?? 0)),
        ];
    }

    private function decodeNote($note): array
    {
        if (!is_string($note) || trim($note) === '') {
            return [];
        }

        $decoded = json_decode($note, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Update profile
     */
    public function update(Request $request) : \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:5',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_code' => $request->country_code ?? $user->country_code,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }
}
