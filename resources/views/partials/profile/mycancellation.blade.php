{{-- My Cancellation Content --}}
<div class="relative z-20 bg-white rounded-2xl sm:rounded-3xl shadow-xl p-4 sm:p-6 md:p-8" data-aos="fade-up" data-aos-delay="100">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div>
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 font-poppins">Riwayat Cancellation</h2>
            <p class="text-xs sm:text-sm text-gray-500 font-poppins mt-0.5">Histori pembatalan booking Anda</p>
        </div>
    </div>

    {{-- Search and Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="flex-1 relative">
            <svg class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   id="cancellationSearch"
                   placeholder="Cari berdasarkan kode booking"
                   class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins text-xs sm:text-sm">
        </div>

        <div class="relative inline-block">
            <button id="cancellationFilterBtn" class="px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl hover:border-[#017249] hover:bg-[#f0fdf4] transition-all duration-300 cursor-pointer" title="Filter">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>
            <div id="cancellationFilterDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                <div class="py-1">
                    <button type="button" class="cancellation-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="all">All Status</button>
                    <button type="button" class="cancellation-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="approved">Disetujui</button>
                    <button type="button" class="cancellation-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="pending">Menunggu</button>
                    <button type="button" class="cancellation-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="rejected">Ditolak</button>
                    <button type="button" class="cancellation-filter-opt block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-[#017249] font-medium transition-colors" data-value="cancelled">Dibatalkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancellation Cards --}}
    <div id="cancellationsContainer" class="space-y-4 sm:space-y-6">
        @forelse($cancellations as $index => $cancellation)
        @php
            $cancelStatusVal = strtolower($cancellation->status ?? 'cancelled');
            $cancelStatusColor = match($cancelStatusVal) {
                'approved' => 'bg-[#017249] text-white',
                'pending' => 'bg-yellow-500 text-white',
                'rejected' => 'bg-orange-500 text-white',
                'cancelled' => 'bg-red-500 text-white',
                default => 'bg-gray-500 text-white',
            };
            $cancelStatusLabel = match($cancelStatusVal) {
                'approved' => 'Disetujui',
                'pending' => 'Menunggu',
                'rejected' => 'Ditolak',
                'cancelled' => 'Dibatalkan',
                default => ucfirst($cancellation->status ?? 'Cancelled'),
            };
        @endphp
        <div class="cancellation-card bg-white rounded-xl sm:rounded-2xl border-2 border-gray-150 overflow-hidden hover:shadow-lg transition-all duration-300"
             data-id="{{ $cancellation->id }}"
             data-status="{{ $cancelStatusVal }}"
             data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 50) }}">

            {{-- Card Header Bar --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 bg-white-50 border-b border-red-100">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-[#017249] font-poppins">Kode Booking</p>
                        <p class="text-xs sm:text-sm font-bold text-gray-800 font-poppins tracking-wide">{{ $cancellation->id ?? ('CN-' . $index) }}</p>
                    </div>
                </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        @if(isset($cancellation->cancelled_at) && $cancellation->cancelled_at)
                            <span class="text-[10px] sm:text-xs text-gray-500 font-poppins hidden sm:block">
                                {{ \Carbon\Carbon::parse($cancellation->cancelled_at)->translatedFormat('d M Y') }}
                            </span>
                        @elseif(isset($cancellation->created_at) && $cancellation->created_at)
                            <span class="text-[10px] sm:text-xs text-gray-500 font-poppins hidden sm:block">
                                {{ \Carbon\Carbon::parse($cancellation->created_at)->translatedFormat('d M Y') }}
                            </span>
                        @endif
                        <span class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold font-poppins {{ $cancelStatusColor }}">
                            {{ $cancelStatusLabel }}
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-4 sm:p-6">
                    {{-- Booking Info --}}
                    <div class="flex items-start gap-3 sm:gap-4 mb-4 sm:mb-5">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                            @if(strtolower($cancellation->area_type ?? '') === 'glamping' || isset($cancellation->deck))
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 font-poppins text-sm sm:text-base">{{ $cancellation->area_type ?? 'Booking' }}</h3>
                            <p class="text-xs sm:text-sm text-gray-500 font-poppins">
                                @if(!empty($cancellation->check_in))
                                    {{ \Carbon\Carbon::parse($cancellation->check_in)->translatedFormat('d F Y') }}
                                    @if(!empty($cancellation->check_out) && $cancellation->check_out !== $cancellation->check_in)
                                        &ndash; {{ \Carbon\Carbon::parse($cancellation->check_out)->translatedFormat('d F Y') }}
                                    @endif
                                @else
                                    Tanggal tidak tersedia
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Details Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-5">
                        {{-- Left: Booking Details --}}
                        <div class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm font-poppins">
                            <div class="flex items-start gap-1">
                                <span class="text-gray-500 w-24 sm:w-28 flex-shrink-0">Area</span>
                                <span class="text-gray-900 font-medium">: {{ $cancellation->area ?? '-' }}</span>
                            </div>
                            @if(!empty($cancellation->deck))
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-24 sm:w-28 flex-shrink-0">Deck</span>
                                    <span class="text-gray-900 font-medium">: {{ $cancellation->deck }}</span>
                                </div>
                            @elseif(!empty($cancellation->outbound))
                                <div class="flex items-start gap-1">
                                    <span class="text-gray-500 w-24 sm:w-28 flex-shrink-0">Outbound</span>
                                    <span class="text-gray-900 font-medium">: {{ $cancellation->outbound }}</span>
                                </div>
                            @endif
                            <div class="flex items-start gap-1">
                                <span class="text-gray-500 w-24 sm:w-28 flex-shrink-0">Peserta</span>
                                <span class="text-gray-900 font-medium">: {{ $cancellation->guests ?? 0 }} orang</span>
                            </div>
                            <div class="flex items-start gap-1">
                                <span class="text-gray-500 w-24 sm:w-28 flex-shrink-0">Total Booking</span>
                                <span class="text-gray-700 font-semibold line-through opacity-60">: Rp {{ number_format($cancellation->total ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Right: Refund Info --}}
                        <div class="bg-white-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-gray-300">
                            <div class="flex items-center gap-2 mb-2 sm:mb-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-[#017249] font-poppins font-medium">Total Refund</p>
                            </div>
                            <p class="text-xl sm:text-2xl font-bold text-black-700 font-poppins">
                                Rp {{ number_format($cancellation->refund ?? 0, 0, ',', '.') }}
                            </p>
                            @if(isset($cancellation->refund_status))
                                @php
                                    $refundStatusColor = match($cancellation->refund_status ?? 'pending') {
                                        'completed' => 'text-gray-700',
                                        'processed' => 'text-gray-700',
                                        'pending' => 'text-gray-700',
                                        default => 'text-gray-700',
                                    };
                                    $refundStatusLabel = match($cancellation->refund_status ?? 'pending') {
                                        'completed' => 'Refund Selesai',
                                        'processed' => 'Sedang Diproses',
                                        'pending' => 'Menunggu Proses',
                                        default => ucfirst($cancellation->refund_status ?? 'Pending'),
                                    };
                                @endphp
                                <p class="text-[10px] sm:text-xs font-poppins mt-1 {{ $refundStatusColor }}">{{ $refundStatusLabel }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Cancellation Fee & Reason --}}
                    @php
                        $hasFee = isset($cancellation->cancellation_fee) && $cancellation->cancellation_fee > 0;
                        $hasReason = !empty($cancellation->reason);
                    @endphp
                    @if($hasFee || $hasReason)
                        <div class="grid grid-cols-1 {{ $hasFee && $hasReason ? 'sm:grid-cols-2' : '' }} gap-3 sm:gap-4 mb-4 sm:mb-5 pt-3 sm:pt-4 border-t border-gray-100">
                            @if($hasFee)
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-300">
                                    <p class="text-[10px] sm:text-xs text-[#017249] font-poppins mb-0.5">Biaya Cancellation</p>
                                    <p class="text-sm sm:text-base font-bold text-black-700 font-poppins">
                                        Rp {{ number_format($cancellation->cancellation_fee, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                            @if($hasReason)
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-300">
                                    <p class="text-[10px] sm:text-xs text-[#017249] font-poppins mb-0.5">Alasan Pembatalan</p>
                                    <p class="text-xs sm:text-sm text-black-700 font-poppins">{{ $cancellation->reason }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-gray-100">
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
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-700 mb-1 sm:mb-2 font-poppins">Belum Ada Cancellation</h3>
                <p class="text-gray-400 mb-4 sm:mb-6 font-poppins text-xs sm:text-sm md:text-base">Anda belum pernah membatalkan booking</p>
                <a href="{{ route('profile', ['tab' => 'bookings']) }}" class="inline-block px-6 sm:px-8 py-2.5 sm:py-3 bg-[#017249] text-white rounded-xl sm:rounded-2xl hover:bg-[#015a3a] font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm">
                    Lihat Booking Saya
                </a>
            </div>
        @endforelse
    </div>

    {{-- Load More --}}
    @if($cancellations->count() > 5)
        <div class="text-center mt-6 sm:mt-8">
            <button id="loadMoreCancellation" class="px-6 sm:px-8 py-2.5 sm:py-3 border-2 border-[#017249] text-[#017249] rounded-xl sm:rounded-2xl hover:bg-[#017249] hover:text-white font-semibold transition-all duration-300 font-poppins text-xs sm:text-sm">
                Muat Lebih Banyak
            </button>
        </div>
    @endif
</div>