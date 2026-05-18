@extends('layouts.app')

@section('title', 'Reschedule - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 bg-[#f6fbf8] min-h-screen')
@section('content')
    <div class="w-full max-w-screen-xl mx-auto px-6">
        <div class="min-h-[calc(100svh-6rem-12rem)] py-8">
            <div class="w-full max-w-3xl mx-auto text-center">
                <x-page-heading title="RESCHEDULE" wrapperClass="text-center" />
                <p class="mt-4 text-gray-600">
                    The booking code can be found in your order details page
                </p>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(isset($error) && $error)
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        {{ $error }}
                    </div>
                @endif

                {{-- Search Form --}}
                <form class="mt-8 flex items-center justify-center" action="{{ route('reschedule') }}" method="GET">
                    <label for="redeem-code" class="sr-only">Booking Code</label>
                    <div class="flex w-full max-w-2xl">
                        <input
                            id="redeem-code"
                            type="text"
                            name="code"
                            value="{{ $code ?? request('code') }}"
                            placeholder="Enter your Booking Code"
                            class="w-full px-6 md:px-7 py-3 md:py-4 bg-white/90 border border-[#146B4A] rounded-l-full rounded-r-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#146B4A] focus:border-[#146B4A] uppercase tracking-wider" />
                        <button
                            type="submit"
                            class="px-6 md:px-8 py-3 md:py-4 bg-[#146B4A] text-white font-medium rounded-r-full rounded-l-none hover:bg-[#115a3e] focus:outline-none focus:ring-2 focus:ring-[#146B4A] -ml-px">
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
                                    @elseif($booking->booking_type === 'outbound' && $booking->bookingOutbounds->first())
                                        @php $outbound = $booking->bookingOutbounds->first(); @endphp
                                        <p><span class="text-gray-500">Activity Date:</span> <span class="font-medium">{{ $outbound->schedule_date?->format('d M Y') }}</span></p>
                                        <p><span class="text-gray-500">Activity:</span> <span class="font-medium">{{ $outbound->outbound?->name ?? '-' }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Reschedule Action --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-center">
                            <a href="{{ route('reschedule.form', ['token' => $booking->token_code]) }}"
                               class="inline-flex items-center justify-center px-8 py-3 bg-[#017249] text-white font-semibold rounded-xl hover:bg-[#015a3a] transition-colors">
                                Reschedule Sekarang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/reschedule.js')
@endpush
