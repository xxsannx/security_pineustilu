@extends('layouts.app')

@section('title', 'Order Details - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 w-full max-w-4xl mx-auto px-6 pb-16')

@section('content')
        <!-- Header -->
        <div class="text-center mb-8">
            <x-page-heading
                title="Order Details"
                wrapperClass="text-center"
                titleClass="text-3xl md:text-4xl font-extrabold text-brand-primary tracking-wider"
                titleStyle="font-family: 'Bizon', sans-serif;" />
            @if(($bookingType ?? 'glamping') === 'outbound')
            <p class="text-gray-600 text-sm">#OUTBOUND{{ str_pad($booking->id ?? '09287123', 8, '0', STR_PAD_LEFT) }}</p>
            @else
            <p class="text-gray-600 text-sm">#GLAMPING{{ str_pad($booking->id ?? '09287123', 8, '0', STR_PAD_LEFT) }}</p>
            @endif
        </div>

        <!-- Booking Code Card - IMPORTANT FOR GUEST USERS -->
        <div class="bg-gradient-to-r from-[#017249] to-[#0b5a3e] rounded-2xl shadow-lg p-5 mb-6 text-white">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <h3 class="text-sm font-medium opacity-90 mb-1">Booking Code / Kode Reservasi</h3>
                    <p class="text-xs opacity-75 mb-2">Simpan kode ini untuk Reschedule atau Cancellation</p>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3 backdrop-blur-sm">
                    <span id="bookingCode" class="text-xl sm:text-2xl font-bold tracking-widest font-mono">{{ $token ?? 'XXXXXXXXXX' }}</span>
                    <button type="button" id="copyCodeBtn" title="Copy Code"
                        class="p-2 hover:bg-white/20 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/20 text-center sm:text-left">
                <p class="text-xs opacity-75">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Gunakan kode ini di halaman <a href="{{ route('reschedule') }}" class="underline hover:no-underline">Reschedule</a> atau <a href="{{ route('cancellation') }}" class="underline hover:no-underline">Cancellation</a>
                </p>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-lg border-2 border-[#017249]/20 overflow-hidden">
            <!-- Progress Stepper -->
            <div class="bg-gradient-to-r from-[#f8fffe] to-white p-4 sm:p-6 border-b-2 border-gray-100 overflow-x-auto">
                <div class="flex items-center justify-between relative min-w-[320px]">
                    <!-- Booking -->
                    <div class="flex flex-col items-center z-10 flex-1">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300
                            {{ ($status ?? 'booking') === 'booking' ? 'bg-[#017249] text-white ring-2 sm:ring-4 ring-[#017249]/20' : 'bg-[#017249] text-white' }}">
                            @if(in_array($status ?? 'booking', ['pembayaran', 'berhasil', 'berjalan', 'selesai']))
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-[10px] sm:text-xs font-semibold text-center {{ ($status ?? 'booking') === 'booking' ? 'text-[#017249]' : 'text-gray-600' }}">Booking</span>
                    </div>

                    <!-- Progress Line 1 -->
                    <div class="absolute top-4 sm:top-5 left-[10%] right-[10%] h-0.5 bg-gray-300 -z-0">
                        <div class="h-full bg-[#017249] transition-all duration-500 {{ in_array($status ?? 'booking', ['pembayaran', 'berhasil', 'berjalan', 'selesai']) ? 'w-full' : 'w-0' }}"></div>
                    </div>

                    <!-- Payment -->
                    <div class="flex flex-col items-center z-10 flex-1">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300
                            {{ ($status ?? 'booking') === 'pembayaran' ? 'bg-[#017249] text-white ring-2 sm:ring-4 ring-[#017249]/20' : (in_array($status ?? 'booking', ['berhasil', 'berjalan', 'selesai']) ? 'bg-[#017249] text-white' : 'bg-gray-300 text-gray-600') }}">
                            @if(in_array($status ?? 'booking', ['berhasil', 'berjalan', 'selesai']))
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-[10px] sm:text-xs font-semibold text-center {{ ($status ?? 'booking') === 'pembayaran' ? 'text-[#017249]' : 'text-gray-600' }}">Payment</span>
                    </div>

                    <!-- Confirmed -->
                    <div class="flex flex-col items-center z-10 flex-1">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300
                            {{ ($status ?? 'booking') === 'berhasil' ? 'bg-[#017249] text-white ring-2 sm:ring-4 ring-[#017249]/20' : (in_array($status ?? 'booking', ['berjalan', 'selesai']) ? 'bg-[#017249] text-white' : 'bg-gray-300 text-gray-600') }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-semibold text-center {{ ($status ?? 'booking') === 'berhasil' ? 'text-[#017249]' : 'text-gray-600' }}">Confirmed</span>
                    </div>

                    <!-- In Progress -->
                    <div class="flex flex-col items-center z-10 flex-1">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300
                            {{ ($status ?? 'booking') === 'berjalan' ? 'bg-[#017249] text-white ring-2 sm:ring-4 ring-[#017249]/20' : (($status ?? 'booking') === 'selesai' ? 'bg-[#017249] text-white' : 'bg-gray-300 text-gray-600') }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-semibold text-center leading-tight {{ ($status ?? 'booking') === 'berjalan' ? 'text-[#017249]' : 'text-gray-600' }}"><span class="hidden sm:inline">In </span>Progress</span>
                    </div>

                    <!-- Completed -->
                    <div class="flex flex-col items-center z-10 flex-1">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300
                            {{ ($status ?? 'booking') === 'selesai' ? 'bg-[#017249] text-white ring-2 sm:ring-4 ring-[#017249]/20' : 'bg-gray-300 text-gray-600' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-semibold text-center {{ ($status ?? 'booking') === 'selesai' ? 'text-[#017249]' : 'text-gray-600' }}"><span class="hidden sm:inline">Com</span>pleted</span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 md:p-8">
                <!-- Date Section - Different for outbound vs glamping -->
                @if(($bookingType ?? 'glamping') === 'outbound')
                <!-- Activity Date for Outbound -->
                <div class="mb-8">
                    <div class="bg-gradient-to-br from-[#f8fffe] to-white rounded-2xl p-4 border border-[#017249]/10">
                        <h3 class="text-xs font-semibold text-[#017249] mb-2">Tanggal Kegiatan</h3>
                        <p class="text-sm font-bold text-gray-800">{{ $activityDate ?? 'Monday, Nov 10, 2025' }}</p>
                    </div>
                </div>
                @else
                <!-- Check-In & Check-Out for Glamping -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-[#f8fffe] to-white rounded-2xl p-4 border border-[#017249]/10">
                        <h3 class="text-xs font-semibold text-[#017249] mb-2">Check-In</h3>
                        <p class="text-sm font-bold text-gray-800">{{ $checkin ?? 'Monday, Nov 10, 2025' }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-[#f8fffe] to-white rounded-2xl p-4 border border-[#017249]/10">
                        <h3 class="text-xs font-semibold text-[#017249] mb-2">Check-Out</h3>
                        <p class="text-sm font-bold text-gray-800">{{ $checkout ?? 'Tuesday, Nov 11, 2025' }}</p>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Guest & Reservation Details -->
                    <div class="space-y-6">
                        <!-- Guest Details -->
                        <div>
                            <h2 class="text-base font-bold text-[#017249] mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Guest Details :
                            </h2>
                            <div class="space-y-3 text-sm">
                                <div class="grid grid-cols-2 gap-2">
                                    <span class="font-semibold text-[#017249]">Full Name</span>
                                    <span class="text-gray-700">{{ $name ?? 'Ahmad Surahmat' }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <span class="font-semibold text-[#017249]">Phone Number</span>
                                    <span class="text-gray-700">{{ $phone ?? '08123456789' }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <span class="font-semibold text-[#017249]">Email Address</span>
                                    <span class="text-gray-700 break-words">{{ $email ?? 'Surahmat@Email.Com' }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <span class="font-semibold text-[#017249]">Number of Guests</span>
                                    <span class="text-gray-700">{{ $guestCount ?? '4' }} People</span>
                                </div>
                            </div>
                        </div>

                        <!-- Reservation Details -->
                        <div>
                            <h2 class="text-base font-bold text-[#017249] mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Reservation Details :
                            </h2>
                            <div class="space-y-3 text-sm">
                                @if(($bookingType ?? 'glamping') === 'outbound')
                                <!-- Outbound Details -->
                                <div>
                                    <span class="font-semibold text-[#017249] block mb-1">Paket Outbound</span>
                                    <span class="text-gray-700">{{ $outboundName ?? '-' }}</span>
                                </div>
                                @if(!empty($variantName))
                                <div>
                                    <span class="font-semibold text-[#017249] block mb-1">Varian</span>
                                    <span class="text-gray-700">{{ $variantName }}</span>
                                </div>
                                @endif
                                @else
                                <!-- Glamping Details -->
                                <div>
                                    <span class="font-semibold text-[#017249] block mb-1">Area</span>
                                    <span class="text-gray-700">{{ $area ?? 'Pineus Tilu 1' }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-[#017249] block mb-1">Deck</span>
                                    <span class="text-gray-700">{{ $deck ?? 'Deck 2' }}</span>
                                </div>
                                @endif
                                @if(isset($amenities) && count($amenities) > 0)
                                <div>
                                    <span class="font-semibold text-[#017249] block mb-2">Add-ons</span>
                                    <ul class="space-y-1.5 ml-1">
                                        @foreach($amenities as $amenity)
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-[#017249] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">{{ $amenity }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @else
                                <div>
                                    <span class="font-semibold text-[#017249] block mb-2">Add-ons</span>
                                    <span class="text-gray-500 text-sm">Tidak ada add-ons</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Payment Details -->
                    <div>
                        <h2 class="text-base font-bold text-[#017249] mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Payment Details :
                        </h2>
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-5 border border-gray-200 space-y-4">
                            <!-- Price -->
                            <div class="space-y-2">
                                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Price</h3>
                                <div class="flex justify-between items-center text-sm">
                                    @if(($bookingType ?? 'glamping') === 'outbound')
                                    <span class="text-gray-700">{{ $outboundName ?? '-' }} ({{ $guestCount ?? 1 }} pax)</span>
                                    @else
                                    <span class="text-gray-700">{{ $area ?? 'Pineus Tilu 1' }}</span>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $basePrice ?? 'Rp 1.000.000' }}</span>
                                </div>
                            </div>

                            <!-- Additional Charges -->
                            @if(isset($additionalCosts) && count($additionalCosts) > 0)
                            <div class="space-y-2 pt-3 border-t border-gray-200">
                                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Additional Charges</h3>
                                @foreach($additionalCosts as $cost)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700">{{ $cost['name'] }}</span>
                                    <span class="text-gray-800">{{ $cost['price'] }}</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="space-y-2 pt-3 border-t border-gray-200">
                                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Additional Charges</h3>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Tidak ada biaya tambahan</span>
                                    <span class="text-gray-800">Rp 0</span>
                                </div>
                            </div>
                            @endif

                            <!-- Total -->
                            <div class="pt-4 border-t-2 border-[#017249]/20">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-[#017249]">Total Price</span>
                                    <span class="text-xl font-extrabold text-[#017249]">{{ $totalPrice ?? 'Rp 1.300.000' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-6 md:px-8 py-6 border-t-2 border-gray-100 flex flex-col sm:flex-row gap-3 justify-end">
                <button type="button" id="backButton"
                    class="px-6 py-3 rounded-2xl border-2 border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200 text-center cursor-pointer">
                    Back
                </button>
                @if(($status ?? 'booking') === 'booking')
                <button type="button" data-action="proceed-to-payment"
                    class="px-8 py-3 rounded-2xl bg-gradient-to-r from-[#017249] to-[#0b5a3e] hover:from-[#0b5a3e] hover:to-[#017249] text-white font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] text-center">
                    Continue Order
                </button>
                @elseif(($status ?? 'booking') === 'pembayaran')
                <button type="button" data-action="complete-payment"
                    class="px-8 py-3 rounded-2xl bg-gradient-to-r from-[#017249] to-[#0b5a3e] hover:from-[#0b5a3e] hover:to-[#017249] text-white font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] text-center">
                    Pay Now
                </button>
                @elseif(($status ?? 'booking') === 'berhasil')
                <div class="text-center text-sm text-gray-600 py-2">
                    <svg class="w-5 h-5 inline text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if(($bookingType ?? 'glamping') === 'outbound')
                    Pembayaran berhasil! Harap hadir pada tanggal kegiatan yang dijadwalkan.
                    @else
                    Payment successful! Please check-in on the scheduled date.
                    @endif
                </div>
                @elseif(($status ?? 'booking') === 'berjalan')
                <div class="text-center text-sm text-gray-600 py-2">
                    <svg class="w-5 h-5 inline text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    @if(($bookingType ?? 'glamping') === 'outbound')
                    Selamat bersenang-senang! Nikmati kegiatan outbound Anda.
                    @else
                    Happy camping! Enjoy your glamping experience.
                    @endif
                </div>
                @elseif(($status ?? 'booking') === 'selesai')
                <div class="text-center text-sm text-gray-600 py-2">
                    <svg class="w-5 h-5 inline text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if(($bookingType ?? 'glamping') === 'outbound')
                    Terima kasih telah berkegiatan bersama kami!
                    @else
                    Thank you for camping with us!
                    @endif
                </div>
                @elseif(($status ?? 'booking') === 'dibatalkan')
                <div class="text-center text-sm text-red-600 py-2">
                    <svg class="w-5 h-5 inline text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    This booking has been cancelled.
                </div>
                @endif
            </div>
        </div>

<!-- Snap Payment Modal -->
<div id="snapPaymentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto mx-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-[#017249] to-[#0b5a3e] px-6 md:px-8 py-6 flex items-center justify-between sticky top-0">
            <h2 class="text-xl md:text-2xl font-bold text-white">Complete Payment</h2>
            <button type="button" onclick="closeSnapModal()" class="text-white hover:bg-white/20 p-2 rounded-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 md:p-8">
            <!-- Loading State -->
            <div id="snapLoading" class="text-center">
                <div class="inline-block">
                    <svg class="animate-spin h-10 w-10 text-[#017249]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 mt-4">Loading payment gateway...</p>
            </div>

            <!-- Snap Container -->
            <div class="flex justify-center w-full">
                <div id="snap-container" class="w-full max-w-[350px]" style="min-height: 700px; display: none;"></div>
            </div>

            <!-- Error State -->
            <div id="snapError" class="hidden text-center">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-10a9 9 0 110 18 9 9 0 010-18z"/>
                </svg>
                <p id="snapErrorMessage" class="text-red-600 font-semibold mb-4"></p>
                <button type="button" onclick="closeSnapModal()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
@include('partials.modals.modal-cancel-booking')

@push('scripts')
<script>
    // Set update URL for the external script
    document.body.dataset.updateStatusUrl = "{{ route('reservasi.update-status', ['token' => $token]) }}";
    // Set booking type for redirect after cancellation
    document.body.dataset.bookingType = "{{ $bookingType ?? 'glamping' }}";
    // Set booking token for payment
    document.body.dataset.bookingToken = "{{ $token }}";
</script>
@vite('resources/js/pages/cancel-booking-modal.js')
@vite('resources/js/pages/detail-pesanan.js')
@endpush
@endsection
