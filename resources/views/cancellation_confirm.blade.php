@extends('layouts.app')

@section('title', 'Confirm Cancellation - Pineus Tilu')

@section('mainClass', 'pt-24 bg-[#f6fbf8] min-h-screen')
@section('content')
    <div class="w-full max-w-screen-xl mx-auto px-6">
        <div class="min-h-[calc(100svh-6rem-12rem)] py-8">
            <div class="w-full max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 md:p-8 text-left">
                    <div class="flex items-start justify-between mb-6 pb-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-lg font-bold text-[#017249]">Ringkasan Reservasi</h3>
                            <p class="text-sm text-gray-500">Kode: {{ $booking->token_code }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $booking->status->value === 'berhasil' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $booking->status->label() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-[#017249] mb-3">Guest Details</h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="text-gray-500">Full Name:</span> <span class="font-medium">{{ $booking->guest_name }}</span></p>
                                <p><span class="text-gray-500">Phone Number:</span> <span class="font-medium">{{ $booking->guest_phone }}</span></p>
                                <p><span class="text-gray-500">Email Address:</span> <span class="font-medium">{{ $booking->guest_email }}</span></p>
                            </div>

                            <h4 class="text-sm font-semibold text-[#017249] mt-6 mb-3">Reservation Details</h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="text-gray-500">Type:</span> <span class="font-medium capitalize">{{ $booking->booking_type }}</span></p>
                                @if($booking->booking_type === 'glamping' && $booking->bookingDetails->first())
                                    @php $detail = $booking->bookingDetails->first(); @endphp
                                    <p><span class="text-gray-500">Check-In</span></p>
                                    <p class="font-medium">{{ $detail->check_in?->format('l, d M Y') }}</p>
                                    <p class="text-gray-500 mt-2">Area</p>
                                    <p class="font-medium">{{ $detail->unit?->area?->name ?? '-' }}</p>
                                @elseif($booking->booking_type === 'outbound' && $booking->bookingOutbounds->first())
                                    @php $outbound = $booking->bookingOutbounds->first(); @endphp
                                    <p class="text-gray-500">Activity Date</p>
                                    <p class="font-medium">{{ $outbound->schedule_date?->format('l, d M Y') }}</p>
                                    <p class="text-gray-500 mt-2">Activity</p>
                                    <p class="font-medium">{{ $outbound->outbound?->name ?? '-' }}</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-[#017249] mb-3">Payment Details</h4>

                            <div class="p-4 border rounded-xl bg-white">
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <div>PRICE</div>
                                    <div class="font-medium">
                                        @if($booking->booking_type === 'glamping' && $booking->bookingDetails->first())
                                            Rp {{ number_format($booking->bookingDetails->first()->total_price ?? 0, 0, ',', '.') }}
                                        @elseif($booking->booking_type === 'outbound' && $booking->bookingOutbounds->first())
                                            Rp {{ number_format($booking->bookingOutbounds->first()->total_price ?? 0, 0, ',', '.') }}
                                        @else
                                            Rp 0
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <div>Cancellation Fee</div>
                                        <div>Rp {{ number_format($cancellation_fee ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <div class="mt-4 border-t pt-4">
                                    <div class="text-sm text-gray-700">Total Refund</div>
                                    <div class="text-xl font-bold text-[#017249]">Rp {{ number_format($refund_amount ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('cancellation.refund') }}" method="POST" id="refund-form" class="mt-6">
                        @csrf
                        <input type="hidden" name="code" value="{{ $code }}">

                        <div class="mt-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan (opsional)</label>
                            <textarea
                                id="reason"
                                name="reason"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Tuliskan alasan pembatalan Anda..."></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="inline-flex items-start space-x-3">
                                <input id="accept-terms" name="accept_terms" type="checkbox" class="mt-1 h-4 w-4 text-green-600 border-gray-300 rounded" />
                                <span class="text-sm text-gray-700">Dengan Menyetujui ini saya mengetahui bahwa pembatalan reservasi tidak bisa dibatalkan</span>
                            </label>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <a href="{{ route('cancellation', ['code' => $code]) }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl">Back</a>
                            <button id="process-refund-btn" type="submit" class="px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors disabled:opacity-50" disabled>
                                Proses Refund
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/cancellation-confirm.js')
@endpush
