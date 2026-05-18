{{-- Promo Ramadhan Section - Unified for All Areas --}}
@php
    // Detect if this is a cabin area based on passed variables
    $isCabin = isset($cabinTier) && !empty($cabinTier);
    $areaLabel = $isCabin ? ('Cabin ' . ($cabinTier ?? 'VVIP')) : ($areaName ?? 'Area');
    
    // Check for ramadan price data
    $hasRamadanWeekday = isset($prices['ramadan_weekday']) && !empty($prices['ramadan_weekday']);
    $hasRamadanWeekend = isset($prices['ramadan_weekend']) && !empty($prices['ramadan_weekend']);
    
    // Detect if prices are array format (regular area) or single format (cabin)
    $isArrayFormat = $hasRamadanWeekday && is_array($prices['ramadan_weekday']) && isset($prices['ramadan_weekday'][0]);
@endphp

<section class="py-6 bg-white relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Main Card Container - Ramadhan Yellow Theme --}}
        <div class="bg-gradient-to-br from-[#8B6914] via-[#A67C00] to-[#8B6914] border border-yellow-500/30 shadow-lg overflow-hidden text-white relative rounded-2xl">
            {{-- Decorative Elements Inside Card --}}
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-10 left-10 w-40 h-40">
                    <svg viewBox="0 0 100 100" fill="currentColor" class="w-full h-full">
                        <path d="M50 0C50 27.6 27.6 50 0 50c27.6 0 50 22.4 50 50 0-27.6 22.4-50 50-50C72.4 50 50 27.6 50 0z"/>
                    </svg>
                </div>
                <div class="absolute bottom-10 right-10 w-32 h-32">
                    <svg viewBox="0 0 100 100" fill="currentColor" class="w-full h-full">
                        <path d="M50 0C50 27.6 27.6 50 0 50c27.6 0 50 22.4 50 50 0-27.6 22.4-50 50-50C72.4 50 50 27.6 50 0z"/>
                    </svg>
                </div>
            </div>
            
            {{-- Crescent Moon Decoration --}}
            <div class="absolute top-8 right-8 md:top-12 md:right-12 w-16 h-16 md:w-20 md:h-20 opacity-20">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full text-yellow-300">
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
            </div>

            {{-- Card Inner Padding --}}
            <div class="px-6 py-10 md:px-10 md:py-12 relative z-10">
                {{-- Header --}}
                <div class="text-center mb-14" data-aos="fade-up" data-aos-duration="600">
                    {{-- Promo Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/20 backdrop-blur-sm border border-amber-400/30 mb-6 rounded-2xl">
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                        <span class="text-sm font-semibold text-amber-300 tracking-wide">SPECIAL OFFER</span>
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide" style="font-family:'Bizon',ui-sans-serif,system-ui;">
                        PROMO 
                        <span class="block mt-2 bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-400 bg-clip-text text-transparent">
                            RAMADHAN 2026
                        </span>
                    </h2>
                    
                    {{-- Decorative Line --}}
                    <div class="flex items-center justify-center gap-3 mt-6">
                        <div class="w-12 h-px bg-gradient-to-r from-transparent to-amber-400/50"></div>
                        <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                        <div class="w-12 h-px bg-gradient-to-l from-transparent to-amber-400/50"></div>
                    </div>
                </div>

                <div class="max-w-xl mx-auto" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    <div class="space-y-4 mb-8">
                        @if($hasRamadanWeekday || $hasRamadanWeekend)
                            {{-- Weekdays --}}
                            @if($hasRamadanWeekday)
                            <div class="group">
                                <button type="button" 
                                    class="ft-toggle w-full rounded-2xl text-left flex items-center justify-between px-5 py-4 bg-amber-500/10 backdrop-blur-sm border border-amber-400/20 hover:bg-amber-500/20 transition-all duration-300 cursor-pointer" 
                                    data-target="promo-weekdays" data-group="promo" aria-expanded="false">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 bg-amber-400/20 flex items-center justify-center rounded-2xl">
                                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <span class="font-semibold text-lg">Weekdays (Sun - Thu)</span>
                                    </div>
                                    <svg class="w-5 h-5 text-amber-300/70 ft-icon transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <div id="promo-weekdays" class="promo-panel bg-amber-500/5 backdrop-blur-sm p-5 mt-3 hidden border border-amber-400/10 rounded-2xl">
                                    <div class="space-y-3">
                                        @if($isArrayFormat)
                                            {{-- Regular Area - Array Format --}}
                                            @foreach($prices['ramadan_weekday'] as $index => $priceData)
                                                <div class="flex justify-between items-center py-2 {{ $index < count($prices['ramadan_weekday']) - 1 ? 'border-b border-amber-400/10' : '' }}">
                                                    <span class="text-white/80">{{ $priceData['unit_names'] }} ({{ $priceData['tent_type'] }})</span>
                                                    <span class="font-bold text-amber-300 text-lg">{{ $formatRupiah($priceData['price']) }}<span class="text-sm font-normal text-white/60">/night</span></span>
                                                </div>
                                            @endforeach
                                        @else
                                            {{-- Cabin - Single Price Format --}}
                                            <div class="flex justify-between items-center py-2">
                                                <span class="text-white/90 font-medium">{{ $areaLabel }}</span>
                                                <span class="font-bold text-amber-300 text-xl">{{ $formatRupiah($prices['ramadan_weekday']['price']) }}<span class="text-sm font-normal text-white/60">/night</span></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Weekend --}}
                            @if($hasRamadanWeekend)
                            <div class="group">
                                <button type="button" 
                                    class="ft-toggle w-full rounded-2xl text-left flex items-center justify-between px-5 py-4 bg-amber-500/10 backdrop-blur-sm border border-amber-400/20 hover:bg-amber-500/20 transition-all duration-300 cursor-pointer" 
                                    data-target="promo-weekend" data-group="promo" aria-expanded="false">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 bg-yellow-400/20 flex items-center justify-center rounded-2xl">
                                            <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </span>
                                        <span class="font-semibold text-lg">Weekend (Fri - Sat)</span>
                                    </div>
                                    <svg class="w-5 h-5 text-yellow-300/70 ft-icon transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <div id="promo-weekend" class="promo-panel bg-yellow-500/5 backdrop-blur-sm p-5 mt-3 hidden border border-yellow-400/10 rounded-2xl">
                                    <div class="space-y-3">
                                        @if($isArrayFormat || (isset($prices['ramadan_weekend'][0])))
                                            {{-- Regular Area - Array Format --}}
                                            @foreach($prices['ramadan_weekend'] as $index => $priceData)
                                                <div class="flex justify-between items-center py-2 {{ $index < count($prices['ramadan_weekend']) - 1 ? 'border-b border-yellow-400/10' : '' }}">
                                                    <span class="text-white/80">{{ $priceData['unit_names'] }} ({{ $priceData['tent_type'] }})</span>
                                                    <span class="font-bold text-yellow-300 text-lg">{{ $formatRupiah($priceData['price']) }}<span class="text-sm font-normal text-white/60">/night</span></span>
                                                </div>
                                            @endforeach
                                        @else
                                            {{-- Cabin - Single Price Format --}}
                                            <div class="flex justify-between items-center py-2">
                                                <span class="text-white/90 font-medium">{{ $areaLabel }}</span>
                                                <span class="font-bold text-yellow-300 text-xl">{{ $formatRupiah($prices['ramadan_weekend']['price']) }}<span class="text-sm font-normal text-white/60">/night</span></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        @else
                            {{-- No promo data available --}}
                            <div class="text-center py-8 text-white/60">
                                <p>Promo prices are not available for this area.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Notes Box --}}
                    <div class="flex items-start gap-3 p-5 bg-amber-500/10 backdrop-blur-sm border border-amber-400/20 rounded-2xl">
                        <div class="w-10 h-10 bg-amber-400/20 flex items-center justify-center flex-shrink-0 rounded-2xl">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-amber-300 mb-1">Notes</p>
                            <p class="text-sm text-white/80">This promotional price is valid exclusively during Ramadan. Book now before it's sold out!</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Card Inner Padding --}}
        </div>
        {{-- End Main Card Container --}}
    </div>
</section>
