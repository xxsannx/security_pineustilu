@extends('layouts.app')

@section('title', 'Cancellation - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 bg-[#f6fbf8] min-h-screen')
@section('content')
    <div class="w-full max-w-screen-xl mx-auto px-6">
        <div class="min-h-[calc(100svh-6rem-12rem)] py-8">
            <div class="w-full max-w-3xl mx-auto text-center">
                <x-page-heading title="CANCELLATION" wrapperClass="text-center" />
                <p class="mt-4 text-gray-600">
                    Insert your booking code and email address to find your reservation cancel it. The booking code can be found in your order details page.
                </p>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if(isset($error) && $error)
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        {{ $error }}
                    </div>
                @endif

                {{-- Search Form --}}
                <form class="mt-8 flex flex-col items-center justify-center gap-4" action="{{ route('cancellation') }}" method="GET">
                    <div class="w-full max-w-2xl">
                        <label for="cancel-redeem" class="block text-sm font-medium text-gray-700 mb-2">Booking Code</label>
                        <input
                            id="cancel-redeem"
                            type="text"
                            name="code"
                            value="{{ $code ?? request('code') }}"
                            placeholder="Enter your Booking Code"
                            class="w-full px-6 md:px-7 py-3 md:py-4 bg-white/90 border border-[#146B4A] rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#146B4A] focus:border-[#146B4A] uppercase tracking-wider" />
                    </div>
                    <div class="w-full max-w-2xl">
                        <label for="cancellation-email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input
                            id="cancellation-email"
                            type="email"
                            name="email"
                            value="{{ $email ?? request('email') }}"
                            placeholder="Enter your Email Address"
                            class="w-full px-6 md:px-7 py-3 md:py-4 bg-white/90 border border-[#146B4A] rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#146B4A] focus:border-[#146B4A]" />
                    </div>
                    <div class="w-full max-w-2xl">
                        <button
                            type="submit"
                            class="w-full px-6 md:px-8 py-3 md:py-4 bg-[#146B4A] text-white font-medium rounded-xl hover:bg-[#115a3e] focus:outline-none focus:ring-2 focus:ring-[#146B4A]">
                            Search
                        </button>
                    </div>
                </form>

                {{-- Booking Details (if found) --}}
                @if(isset($booking) && $booking)
                    <div class="mt-10 bg-white rounded-2xl shadow-lg border border-gray-200 p-6 md:p-8 text-left">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                            <div>
                                <h3 class="text-lg font-bold text-[#017249]">Booking Found</h3>
                                <p class="text-sm text-gray-500">Code: {{ $booking->token_code }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $booking->status->value === 'berhasil' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $booking->status->label() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Guest Info --}}
                            <div>
                                <h4 class="text-sm font-semibold text-[#017249] mb-3">Guest Information</h4>
                                <div class="space-y-2 text-sm">
                                    <p><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $booking->guest_name }}</span></p>
                                    <p><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $booking->guest_phone }}</span></p>
                                    <p><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $booking->guest_email }}</span></p>
                                </div>
                            </div>

                            {{-- Booking Info --}}
                            <div>
                                <h4 class="text-sm font-semibold text-[#017249] mb-3">Booking Details</h4>
                                <div class="space-y-2 text-sm">
                                    <p><span class="text-gray-500">Type:</span> <span class="font-medium capitalize">{{ $booking->booking_type }}</span></p>
                                    @if($booking->booking_type === 'glamping' && $booking->bookingDetails->first())
                                        @php $detail = $booking->bookingDetails->first(); @endphp
                                        <p><span class="text-gray-500">Check-in:</span> <span class="font-medium">{{ $detail->check_in?->format('d M Y') }}</span></p>
                                        <p><span class="text-gray-500">Check-out:</span> <span class="font-medium">{{ $detail->check_out?->format('d M Y') }}</span></p>
                                        <p><span class="text-gray-500">Area:</span> <span class="font-medium">{{ $detail->unit?->area?->name ?? '-' }}</span></p>
                                        <p><span class="text-gray-500">Total:</span> <span class="font-medium">Rp {{ number_format($detail->total_price ?? 0, 0, ',', '.') }}</span></p>
                                    @elseif($booking->booking_type === 'outbound' && $booking->bookingOutbounds->first())
                                        @php $outbound = $booking->bookingOutbounds->first(); @endphp
                                        <p><span class="text-gray-500">Activity Date:</span> <span class="font-medium">{{ $outbound->schedule_date?->format('d M Y') }}</span></p>
                                        <p><span class="text-gray-500">Activity:</span> <span class="font-medium">{{ $outbound->outbound?->name ?? '-' }}</span></p>
                                        <p><span class="text-gray-500">Total:</span> <span class="font-medium">Rp {{ number_format($outbound->total_price ?? 0, 0, ',', '.') }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Cancellation CTA: redirect to confirmation page --}}
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-start text-left">
                                <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-sm text-red-800 leading-relaxed">
                                    <strong>Perhatian:</strong> Sebelum melanjutkan, pastikan untuk membaca <a href="{{ route('pedoman') }}#kebijakan-heading" class="inline text-red-600 font-bold px-1 underline underline-offset-2 hover:text-red-800 transition-colors" target="_blank">kebijakan</a> pembatalan kami dan memahami biaya pembatalan yang berlaku.
                                </p>
                            </div>

                            <div class="flex items-center justify-center">
                                <a href="{{ route('cancellation.confirm', ['code' => $booking->token_code]) }}" class="w-full sm:w-auto px-8 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors inline-flex items-center justify-center">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancel Booking
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/cancellation.js')
@endpush
