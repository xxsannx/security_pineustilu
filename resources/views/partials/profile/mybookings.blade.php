{{-- My Bookings Content --}}
<div class="relative z-20 bg-white rounded-2xl sm:rounded-3xl shadow-xl p-4 sm:p-6 md:p-8" data-aos="fade-up" data-aos-delay="100">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div>
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 font-poppins">Booking</h2>
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="flex-1 relative">
            <svg class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   id="bookingSearch"
                   placeholder="search"
                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins text-xs sm:text-sm">
        </div>

        <div class="relative inline-block">
            <button id="bookingFilterBtn" class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl hover:border-[#017249] hover:bg-[#f0fdf4] transition-all duration-300 cursor-pointer" title="Filter">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>
            <div id="bookingFilterDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                <div class="py-1">
                    <button type="button" class="booking-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="all">All Status</button>
                    <button type="button" class="booking-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="selesai">Selesai</button>
                    <button type="button" class="booking-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="berhasil">Berhasil</button>
                    <button type="button" class="booking-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="proses">Proses / Menunggu</button>
                    <button type="button" class="booking-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="pembayaran">Pembayaran</button>
                    <button type="button" class="booking-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="dibatalkan">Dibatalkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Booking Cards --}}
<div id="bookingsContainer" class="space-y-4 sm:space-y-6">
    @forelse($bookings as $index => $booking)
        @php
            $statusValue = is_object($booking->status) ? $booking->status->value : $booking->status;
            $statusLabel = is_object($booking->status) ? $booking->status->label() : ucfirst($statusValue);
            $statusColor = match($statusValue) {
                'proses' => 'bg-gray-500 text-white',
                'pembayaran' => 'bg-yellow-500 text-white',
                'berhasil' => 'bg-[#017249] text-white',
                'berjalan' => 'bg-blue-500 text-white',
                'selesai' => 'bg-emerald-600 text-white',
                'dibatalkan' => 'bg-red-500 text-white',
                default => 'bg-gray-400 text-white',
            };
        @endphp
        <div class="booking-card bg-white rounded-xl sm:rounded-2xl border border-gray-200 p-4 sm:p-6 hover:shadow-lg transition-all duration-300"
             data-id="{{ $booking->id }}"
             data-status="{{ strtolower($statusLabel) }}" data-status-val="{{ strtolower($statusValue) }}"
             data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 50) }}">

            {{-- Header --}}
            <div class="flex items-start justify-between mb-3 sm:mb-4 pb-3 sm:pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Icon --}}
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                        @if(strtolower($booking->area_type ?? '') === 'glamping' || isset($booking->deck))
                            {{-- Glamping/Cabin Icon --}}
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        @else
                            {{-- Outbound/Activity Icon --}}
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        @endif
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 font-poppins text-sm sm:text-base">{{ $booking->area_type ?? 'Booking' }}</h3>
                        <p class="text-xs sm:text-sm text-gray-500 font-poppins">{{ \Carbon\Carbon::parse($booking->check_in)->format('d F Y') }}</p>
                    </div>
                </div>

                {{-- Status Badge --}}
                <span class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold font-poppins {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-6 mb-3 sm:mb-4">
                <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm font-poppins">
                    <div class="flex items-center">
                        <span class="text-gray-600 w-20 sm:w-24">Area</span>
                        <span class="text-gray-900 font-medium">: {{ $booking->area }}</span>
                    </div>
                    @if(isset($booking->deck))
                        <div class="flex items-center">
                            <span class="text-gray-600 w-20 sm:w-24">Deck</span>
                            <span class="text-gray-900 font-medium">: {{ $booking->deck }}</span>
                        </div>
                    @elseif(isset($booking->outbound))
                        <div class="flex items-center">
                            <span class="text-gray-600 w-20 sm:w-24">Outbound</span>
                            <span class="text-gray-900 font-medium">: {{ $booking->outbound }}</span>
                        </div>
                    @endif
                    <div class="flex items-center">
                        <span class="text-gray-600 w-20 sm:w-24">Participants</span>
                        <span class="text-gray-900 font-medium">: {{ $booking->guests }} People</span>
                    </div>
                </div>

                <div class="text-left md:text-right mt-2 md:mt-0">
                    <p class="text-xs sm:text-sm text-gray-600 font-poppins mb-0.5 sm:mb-1">Total Price</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 font-poppins">Rp. {{ number_format($booking->total, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-gray-100">
                @php
                    $bookingDetailsData = [
                        'id' => $booking->id ?? 0,
                        'area_type' => $booking->area_type ?? 'Glamping',
                        'status' => is_object($booking->status) ? $booking->status->value : $booking->status,
                        'check_in' => $booking->check_in ?? null,
                        'check_out' => $booking->check_out ?? null,
                        'area' => $booking->area ?? null,
                        'deck' => $booking->deck ?? null,
                        'outbound' => $booking->outbound ?? null,
                        'variant' => $booking->variant ?? null,
                        'guests' => $booking->guests ?? 0,
                        'guest_name' => $booking->guest_name ?? '',
                        'guest_phone' => $booking->guest_phone ?? '',
                        'guest_email' => $booking->guest_email ?? '',
                        'amenities' => $booking->amenities ?? [],
                        'total' => $booking->total ?? 0,
                        'extra_charge' => $booking->extra_charge ?? 0,
                        'booking_code' => $booking->token_code ?? ''
                    ];
                    $statusVal = is_object($booking->status) ? $booking->status->value : $booking->status;
                @endphp
                <button type="button"
                    data-booking-details='@json($bookingDetailsData)'
                    class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 text-[#017249] border border-[#017249] rounded-xl sm:rounded-2xl hover:bg-[#017249] hover:text-white font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm text-center cursor-pointer">
                    Order Details
                </button>
                @if(in_array($statusVal, ['selesai', 'berhasil']))
                    <a href="{{ route('reservasi.glamping') }}" class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 bg-[#017249] text-white rounded-xl sm:rounded-2xl hover:bg-[#015a3a] font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm text-center">
                        Book Again
                    </a>
                @else
                    <a href="https://wa.me/6281234567890" target="_blank" class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 bg-[#017249] text-white rounded-xl sm:rounded-2xl hover:bg-[#015a3a] font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm text-center">
                        Contact Admin
                    </a>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl p-8 sm:p-12 text-center" data-aos="fade-up">
            <div class="w-16 h-16 sm:w-24 sm:h-24 mx-auto mb-4 sm:mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 mb-1 sm:mb-2 font-poppins">No Bookings Yet</h3>
            <p class="text-gray-500 mb-4 sm:mb-6 font-poppins text-xs sm:text-sm md:text-base">You don't have any active bookings</p>
            <a href="{{ route('reservasi.glamping') }}" class="inline-block px-6 sm:px-8 py-2.5 sm:py-3 bg-[#017249] text-white rounded-xl sm:rounded-2xl hover:bg-[#015a3a] font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm">
                Make a Reservation
            </a>
        </div>
    @endforelse
</div>

{{-- Load More --}}
@if($bookings->count() > 5)
    <div class="text-center mt-6 sm:mt-8" data-aos="fade-up">
        <button id="loadMoreBooking" class="px-6 sm:px-8 py-2.5 sm:py-3 border-2 border-[#017249] text-[#017249] rounded-xl sm:rounded-2xl hover:bg-[#017249] hover:text-white font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm">
            Load More
        </button>
    </div>
@endif
