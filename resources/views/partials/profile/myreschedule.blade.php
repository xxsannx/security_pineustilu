{{-- My Reschedule Content --}}
<div class="relative z-20 bg-white rounded-2xl sm:rounded-3xl shadow-xl p-4 sm:p-6 md:p-8" data-aos="fade-up" data-aos-delay="100">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div>
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 font-poppins">Riwayat Reschedule</h2>
            <p class="text-xs sm:text-sm text-gray-500 font-poppins mt-0.5">Histori perubahan jadwal booking Anda</p>
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="flex-1 relative">
            <svg class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" id="rescheduleSearch" placeholder="Cari berdasarkan kode booking atau area..."
                class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins text-xs sm:text-sm">
        </div>

        <div class="relative inline-block">
            <button id="rescheduleFilterBtn" class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl hover:border-[#017249] hover:bg-[#f0fdf4] transition-all duration-300 cursor-pointer" title="Filter">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
            <div id="rescheduleFilterDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                <div class="py-1">
                    <button type="button" class="reschedule-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="all">All Status</button>
                    <button type="button" class="reschedule-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="rescheduled">Rescheduled / Approved</button>
                    <button type="button" class="reschedule-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="pending">Pending</button>
                    <button type="button" class="reschedule-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="rejected">Rejected</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reschedule Cards --}}
    <div id="reschedulesContainer" class="space-y-4 sm:space-y-6">
        @forelse($reschedules as $index => $reschedule)
        @php
            $rescheduleStatusVal = strtolower($reschedule->status ?? 'rescheduled');
            $rescheduleStatusColor = match ($rescheduleStatusVal) {
                'approved', 'rescheduled' => 'bg-[#017249] text-white',
                'pending' => 'bg-yellow-500 text-white',
                'rejected' => 'bg-red-500 text-white',
                default => 'bg-blue-500 text-white',
            };
            $rescheduleStatusLabel = match ($rescheduleStatusVal) {
                'approved', 'rescheduled' => 'Rescheduled',
                'pending' => 'Pending',
                'rejected' => 'Rejected',
                default => ucfirst($reschedule->status ?? 'Rescheduled'),
            };
            
            // Map approved to rescheduled for filter
            if ($rescheduleStatusVal === 'approved') $rescheduleStatusVal = 'rescheduled';
        @endphp
        <div class="reschedule-card bg-gray-50 rounded-xl sm:rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300"
            data-id="{{ $reschedule->id }}"
            data-status="{{ $rescheduleStatusVal }}"
            data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 50) }}">

            {{-- Card Header Bar --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 bg-white border-b border-gray-100">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-gray-400 font-poppins">Kode Reschedule</p>
                        <p class="text-xs sm:text-sm font-bold text-gray-800 font-poppins tracking-wide">
                            {{ $reschedule->id ?? ('RS-' . $index) }}
                        </p>
                    </div>
                </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        @if(isset($reschedule->created_at) && $reschedule->created_at)
                            <span class="text-[10px] sm:text-xs text-gray-400 font-poppins hidden sm:block">
                                {{ \Carbon\Carbon::parse($reschedule->created_at)->format('d M Y') }}
                            </span>
                        @endif
                        <span
                            class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold font-poppins {{ $rescheduleStatusColor }}">
                            {{ $rescheduleStatusLabel }}
                        </span>
                    </div>
                </div>

                {{-- Card Body: Before & After --}}
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-4 sm:gap-6 items-stretch">

                        {{-- Old Schedule --}}
                        <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-5 border border-gray-200">
                            <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <div
                                    class="w-9 h-9 sm:w-11 sm:h-11 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                                    @if(strtolower($reschedule->area_type ?? '') === 'glamping' || isset($reschedule->deck))
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-poppins uppercase tracking-wide">Jadwal Lama
                                    </p>
                                    <h3 class="font-bold text-gray-900 font-poppins text-xs sm:text-sm">
                                        {{ $reschedule->area_type ?? 'Booking' }}
                                    </h3>
                                </div>
                            </div>

                            <div class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm font-poppins">
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Area</span>
                                    <span class="text-gray-900 font-medium">:
                                        {{ $reschedule->old_area ?? $reschedule->area ?? '-' }}</span>
                                </div>
                                @if(!empty($reschedule->old_deck) || !empty($reschedule->deck))
                                    <div class="flex items-start gap-1">
                                        <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Deck</span>
                                        <span class="text-gray-900 font-medium">:
                                            {{ $reschedule->old_deck ?? $reschedule->deck }}</span>
                                    </div>
                                @elseif(!empty($reschedule->old_outbound) || !empty($reschedule->outbound))
                                    <div class="flex items-start gap-1">
                                        <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Outbound</span>
                                        <span class="text-gray-900 font-medium">:
                                            {{ $reschedule->old_outbound ?? $reschedule->outbound }}</span>
                                    </div>
                                @endif
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Check-in</span>
                                    <span class="text-gray-900 font-medium">:
                                        @if($reschedule->old_check_in)
                                            {{ \Carbon\Carbon::parse($reschedule->old_check_in)->translatedFormat('d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                @if($reschedule->old_check_out && $reschedule->old_check_out !== $reschedule->old_check_in)
                                    <div class="flex items-start gap-1">
                                        <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Check-out</span>
                                        <span class="text-gray-900 font-medium">:
                                            {{ \Carbon\Carbon::parse($reschedule->old_check_out)->translatedFormat('d F Y') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Peserta</span>
                                    <span class="text-gray-900 font-medium">: {{ $reschedule->guests ?? 0 }} orang</span>
                                </div>
                            </div>

                            <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                                <p class="text-[10px] sm:text-xs text-gray-400 font-poppins mb-0.5">Total Harga</p>
                                <p
                                    class="text-sm sm:text-base font-bold text-gray-700 font-poppins line-through opacity-60">
                                    Rp {{ number_format($reschedule->old_total ?? $reschedule->total ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Arrow / Reschedule Indicator --}}
                        <div class="flex flex-row md:flex-col items-center justify-center gap-2 py-2 sm:py-0">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 rotate-90 md:rotate-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-semibold text-blue-600 font-poppins text-center">Diubah
                                ke</span>
                        </div>

                        {{-- New Schedule --}}
                        <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-5 border border-grey-200">
                            <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <div
                                    class="w-9 h-9 sm:w-11 sm:h-11 bg-white rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    @if(strtolower($reschedule->area_type ?? '') === 'glamping' || isset($reschedule->deck))
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-poppins uppercase tracking-wide">Jadwal Baru
                                    </p>
                                    <h3 class="font-bold text-gray-900 font-poppins text-xs sm:text-sm">
                                        {{ $reschedule->area_type ?? 'Booking' }}
                                    </h3>
                                </div>
                            </div>

                            <div class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm font-poppins">
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Area</span>
                                    <span class="text-gray-900 font-medium">: {{ $reschedule->area ?? '-' }}</span>
                                </div>
                                @if(!empty($reschedule->deck))
                                    <div class="flex items-start gap-1">
                                        <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Deck</span>
                                        <span class="text-gray-900 font-medium">: {{ $reschedule->deck }}</span>
                                    </div>
                                @elseif(!empty($reschedule->outbound))
                                    <div class="flex items-start gap-1">
                                        <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Outbound</span>
                                        <span class="text-gray-900 font-medium">: {{ $reschedule->outbound }}</span>
                                    </div>
                                @endif
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Check-in</span>
                                    <span class="text-gray-900 font-medium">:
                                        @if($reschedule->new_check_in)
                                            {{ \Carbon\Carbon::parse($reschedule->new_check_in)->translatedFormat('d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                @if($reschedule->new_check_out && $reschedule->new_check_out !== $reschedule->new_check_in)
                                    <div class="flex items-start gap-1">
                                        <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Check-out</span>
                                        <span class="text-gray-900 font-medium">:
                                            {{ \Carbon\Carbon::parse($reschedule->new_check_out)->translatedFormat('d F Y') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-20 sm:w-24 flex-shrink-0">Peserta</span>
                                    <span class="text-gray-900 font-medium">: {{ $reschedule->guests ?? 0 }} orang</span>
                                </div>
                            </div>

                            <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-green-200">
                                <p class="text-[10px] sm:text-xs text-gray-400 font-poppins mb-0.5">Total Harga Baru</p>
                                <p class="text-sm sm:text-base font-bold text-gray-900 font-poppins">
                                    Rp {{ number_format($reschedule->total ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Reschedule Fee & Reason --}}
                    <div
                        class="mt-4 sm:mt-5 pt-4 sm:pt-5 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        @if(isset($reschedule->reschedule_fee) && $reschedule->reschedule_fee > 0)
                            <div class="bg-orange-50 rounded-lg p-3 sm:p-4 border border-orange-100">
                                <p class="text-[10px] sm:text-xs text-orange-600 font-poppins mb-0.5">Biaya Reschedule</p>
                                <p class="text-sm sm:text-base font-bold text-orange-700 font-poppins">
                                    Rp {{ number_format($reschedule->reschedule_fee, 0, ',', '.') }}
                                </p>
                            </div>
                        @endif
                        @if(!empty($reschedule->reason))
                            <div
                                class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-100 {{ isset($reschedule->reschedule_fee) && $reschedule->reschedule_fee > 0 ? '' : 'sm:col-span-2' }}">
                                <p class="text-[10px] sm:text-xs text-gray-400 font-poppins mb-0.5">Alasan Reschedule</p>
                                <p class="text-xs sm:text-sm text-gray-700 font-poppins">{{ $reschedule->reason }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-4 sm:mt-5">
                        <a href="https://wa.me/{{ config('app.whatsapp_number', '6281234567890') }}" target="_blank"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 bg-[#017249] text-white rounded-xl sm:rounded-2xl hover:bg-[#015a3a] font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm text-center">
                            Hubungi Admin
                        </a>
                        <a href="{{ route('profile', ['tab' => 'bookings']) }}"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 text-[#017249] border border-[#017249] rounded-xl sm:rounded-2xl hover:bg-[#f0fdf4] font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm text-center">
                            Lihat Booking
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 sm:py-16 text-center" data-aos="fade-up">
                <div
                    class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 bg-blue-50 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-700 mb-1 sm:mb-2 font-poppins">Belum Ada
                    Reschedule</h3>
                <p class="text-gray-400 mb-4 sm:mb-6 font-poppins text-xs sm:text-sm md:text-base">Anda belum pernah
                    melakukan perubahan jadwal booking</p>
                <a href="{{ route('profile', ['tab' => 'bookings']) }}"
                    class="inline-block px-6 sm:px-8 py-2.5 sm:py-3 bg-[#017249] text-white rounded-xl sm:rounded-2xl hover:bg-[#015a3a] font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm">
                    Lihat Booking Saya
                </a>
            </div>
        @endforelse
    </div>

    {{-- Load More --}}
    @if($reschedules->count() > 5)
        <div class="text-center mt-6 sm:mt-8">
            <button id="loadMoreReschedule"
                class="px-6 sm:px-8 py-2.5 sm:py-3 border-2 border-[#017249] text-[#017249] rounded-xl sm:rounded-2xl hover:bg-[#017249] hover:text-white font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm">
                Muat Lebih Banyak
            </button>
        </div>
    @endif
</div>