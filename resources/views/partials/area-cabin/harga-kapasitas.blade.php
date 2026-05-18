{{-- Price & Capacity Section - Card Design - VIP Amber Theme --}}
<section class="py-6 bg-white relative overflow-hidden">

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Main Card Container - VIP Gold Theme --}}
        <div
            class="bg-gradient-to-br from-[#6B4E2A] via-[#7D5E33] to-[#6B4E2A] border border-[#9A7540]/30 shadow-lg overflow-hidden text-white relative rounded-2xl">
            {{-- Decorative Elements Inside Card --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-72 h-72 bg-[#E8CFA0] blur-3xl -translate-x-1/2 -translate-y-1/2">
                </div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#D4AF6A] blur-3xl translate-x-1/2 translate-y-1/2">
                </div>
            </div>
            {{-- Card Inner Padding --}}
            <div class="px-6 py-10 md:px-10 md:py-12 relative z-10">
                {{-- Section Header --}}
                <div class="text-center mb-14" data-aos="fade-up" data-aos-duration="600">
                    <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide"
                        style="font-family:'Bizon',ui-sans-serif,system-ui;">PRICE & CAPACITY</h2>
                </div>

                @php
                    $seasonLabels = ['weekday' => 'Weekdays', 'weekend' => 'Weekend', 'high_season' => 'High Season'];
                    $cabinPricesRaw = $prices ?? [];
                    $prices = [];
                    foreach ($seasonLabels as $key => $label) {
                        if (isset($cabinPricesRaw[$key])) {
                            $prices[$key][] = [
                                'label' => 'Cabin ' . ($cabinTier ?? 'VVIP'),
                                'unit_names' => 'Cabin ' . ($cabinTier ?? 'VVIP'),
                                'tent_type' => 'Cabin',
                                'price' => $cabinPricesRaw[$key]['price'] ?? 0,
                            ];
                        }
                    }
                    $defaultPeople = $unit->default_people ?? 4;
                    $maxPeople = $unit->max_people ?? 8;
                @endphp

                <div class="grid md:grid-cols-2 gap-6 lg:gap-8">
                    {{-- PRICE Card --}}
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl border border-[#D4AF6A]/20 overflow-hidden hover:bg-white/10 transition-all duration-300"
                        data-aos="fade-right" data-aos-duration="600">
                        {{-- Card Header --}}
                        <div class="px-6 py-4 bg-[#B98C4F]/10 border-b border-[#D4AF6A]/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#D4AF6A]/20 rounded-2xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#E8CFA0]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold">PRICE</h3>
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-5 space-y-6">
                            @if (isset($prices) && count($prices))
                                @foreach (['weekday' => 'Weekdays', 'weekend' => 'Weekend', 'high_season' => 'High Season'] as $key => $label)
                                    @if (isset($prices[$key]) && count($prices[$key]))
                                        <div class="group">
                                            <button type="button"
                                                class="ft-toggle w-full text-left flex items-center justify-between px-4 py-3 bg-white/5 border border-[#D4AF6A]/20 rounded-2xl hover:bg-white/10 transition-all duration-300 cursor-pointer"
                                                data-target="price-{{ $key }}" data-group="price"
                                                aria-expanded="false">
                                                <div class="flex items-center gap-3">
                                                    <span
                                                        class="w-8 h-8 bg-[#D4AF6A]/20 rounded-2xl flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-[#E8CFA0]" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                    <span class="font-semibold">{{ $label }}</span>
                                                </div>
                                                <svg class="w-5 h-5 text-white/70 ft-icon transition-transform duration-300"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <div id="price-{{ $key }}"
                                                class="bg-white/5 rounded-2xl p-4 mt-2 hidden border border-[#D4AF6A]/10">
                                                <div class="space-y-2 text-sm">
                                                    @foreach ($prices[$key] as $price)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-white/80">{{ $price['label'] }}</span>
                                                            <span
                                                                class="font-bold text-[#E8CFA0]">{{ $formatRupiah($price['price']) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                {{-- Weekdays --}}
                                <div class="group">
                                    <button type="button"
                                        class="ft-toggle w-full text-left flex items-center justify-between px-4 py-3 bg-white/5 border border-[#D4AF6A]/20 rounded-2xl hover:bg-white/10 transition-all duration-300 cursor-pointer"
                                        data-target="price-weekdays" data-group="price" aria-expanded="false">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="w-8 h-8 bg-[#D4AF6A]/20 rounded-2xl flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#E8CFA0]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <span class="font-semibold">Weekdays</span>
                                        </div>
                                        <svg class="w-5 h-5 text-white/70 ft-icon transition-transform duration-300"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <div id="price-weekdays"
                                        class="bg-white/5 rounded-2xl p-4 mt-4 hidden border border-[#D4AF6A]/10">
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="text-white/80">Cabin {{ $cabinTier ?? 'VVIP' }}</span>
                                                <span class="font-bold text-[#E8CFA0]">IDR 750.000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Weekend --}}
                                <div class="group">
                                    <button type="button"
                                        class="ft-toggle w-full text-left flex items-center justify-between px-4 py-3 bg-white/5 border border-[#D4AF6A]/20 rounded-2xl hover:bg-white/10 transition-all duration-300 cursor-pointer"
                                        data-target="price-weekend" data-group="price" aria-expanded="false">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="w-8 h-8 bg-[#D4AF6A]/20 rounded-2xl flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#E8CFA0]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </span>
                                            <span class="font-semibold">Weekend</span>
                                        </div>
                                        <svg class="w-5 h-5 text-white/70 ft-icon transition-transform duration-300"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <div id="price-weekend"
                                        class="bg-white/5 rounded-2xl p-4 mt-4 hidden border border-[#D4AF6A]/10">
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="text-white/80">Cabin {{ $cabinTier ?? 'VVIP' }}</span>
                                                <span class="font-bold text-[#E8CFA0]">IDR 950.000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- High Season --}}
                                <div class="group">
                                    <button type="button"
                                        class="ft-toggle w-full text-left flex items-center justify-between px-4 py-3 bg-white/5 border border-[#D4AF6A]/20 rounded-2xl hover:bg-white/10 transition-all duration-300 cursor-pointer"
                                        data-target="price-high" data-group="price" aria-expanded="false">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="w-8 h-8 bg-[#D4AF6A]/20 rounded-2xl flex items-center justify-center">
                                                <svg class="w-4 h-4 text-[#E8CFA0]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                                </svg>
                                            </span>
                                            <span class="font-semibold">High Season</span>
                                        </div>
                                        <svg class="w-5 h-5 text-white/70 ft-icon transition-transform duration-300"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <div id="price-high"
                                        class="bg-white/5 rounded-2xl p-4 mt-4 hidden border border-[#D4AF6A]/10">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-white/80">Cabin {{ $cabinTier ?? 'VVIP' }}</span>
                                            <span class="font-bold text-[#E8CFA0]">IDR 1.100.000</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- KAPASITAS Card --}}
                    <div class="bg-white/5 backdrop-blur-sm rounded-2xl border border-[#D4AF6A]/20 overflow-hidden hover:bg-white/10 transition-all duration-300"
                        data-aos="fade-left" data-aos-duration="600" data-aos-delay="100">
                        {{-- Card Header --}}
                        <div class="px-6 py-4 bg-[#B98C4F]/10 border-b border-[#D4AF6A]/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#D4AF6A]/20 rounded-2xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#E8CFA0]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold">CAPACITY</h3>
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-5 space-y-6">
                            {{-- Standar Kapasitas --}}
                            <div class="group">
                                <button type="button"
                                    class="ft-toggle w-full text-left flex items-center justify-between px-4 py-3 bg-white/5 border border-[#D4AF6A]/20 rounded-2xl hover:bg-white/10 transition-all duration-300 cursor-pointer"
                                    data-target="cap-standard" data-group="capacity" aria-expanded="false">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="w-8 h-8 bg-[#D4AF6A]/20 rounded-2xl flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#E8CFA0]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                        <span class="font-semibold">Standard Capacity</span>
                                    </div>
                                    <svg class="w-5 h-5 text-white/70 ft-icon transition-transform duration-300"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div id="cap-standard"
                                    class="bg-white/5 rounded-2xl p-4 mt-4 hidden border border-[#D4AF6A]/10">
                                    <div class="flex items-center justify-center gap-6 mb-3">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-[#E8CFA0]">{{ $defaultPeople ?? 4 }}
                                            </div>
                                            <div class="text-xs text-white/60 uppercase tracking-wider">Standard</div>
                                        </div>
                                        <div class="text-white/40">—</div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-[#E8CFA0]">{{ $maxPeople ?? 6 }}</div>
                                            <div class="text-xs text-white/60 uppercase tracking-wider">Maximum</div>
                                        </div>
                                    </div>
                                    <p
                                        class="text-xs text-white/70 italic text-center border-t border-[#D4AF6A]/10 pt-3 mt-3">
                                        Guests above 2 years old will be charged
                                    </p>
                                </div>
                            </div>

                            {{-- Info Note --}}
                            <div
                                class="flex items-start gap-2 p-3 bg-white/5 rounded-2xl border border-[#D4AF6A]/10 mt-4">
                                <svg class="w-4 h-4 text-[#E8CFA0]/60 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-white/70">
                                    @if (($cabinTier ?? 'VVIP') === 'VVIP')
                                        Cabin VVIP cannot accommodate more than 8 guests. For additional information please contact reservation admin.
                                    @else
                                        Cabin VIP cannot accommodate more than 5 guests. For additional information please contact reservation admin.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Card Inner Padding --}}
        </div>
        {{-- End Main Card Container --}}
    </div>
</section>
