{{-- Deck Schema Section - Enhanced --}}
<section class="py-6" data-aos="fade-up" data-aos-duration="600">
    <div class="max-w-6xl mx-auto px-6">
        {{-- Intro Text --}}
        <div class="text-center mb-10" data-aos="fade-up" data-aos-delay="100">
            {{-- Description  --}}
            <p class="text-[#0b5a3e] text-xl md:text-xl leading-relaxed max-w-6xl mx-auto">
                {{ $area->description ?? 'Experience staying closest to nature at <strong class="text-[#017249]">Pineus Tilu I</strong>, located right by the riverside. Perfect for those who want to relax while enjoying the sound of flowing water and river views just steps from your spot.' }}
            </p>
        </div>

        {{-- Section Title --}}
        <div class="text-center mb-14" data-aos="fade-up" data-aos-delay="150">
            <h2 class="text-2xl md:text-4xl font-extrabold text-[#017249] tracking-wide" style="font-family:'Bizon',ui-sans-serif,system-ui;">DECK LAYOUT</h2>
        </div>

        {{-- Deck Schema Card --}}
        <div class="bg-white shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-500 max-w-6xl mx-auto rounded-2xl"
            data-aos="zoom-in" data-aos-delay="200">
            <div class="p-2">
                @php
                    $deckImage = match ($areaSlug ?? 'pineus-tilu-1') {
                        'pineus-tilu-1' => 'pt1_deck.svg',
                        'pineus-tilu-2' => 'pt2_deck.svg',
                        'pineus-tilu-3-vip' => 'pt3vip_deck.svg',
                        'pineus-tilu-4' => 'pt4_deck.svg',
                        default => 'pt1_deck.svg',
                    };
                    $deckTitle = $areaName ?? 'Pineus Tilu I';
                @endphp
                <div class="relative w-full bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
                    <img src="{{ asset('images/deck/' . $deckImage) }}"
                        alt="{{ $deckTitle }} Layout (PT-{{ $number ?? 1 }})"
                    class="w-full h-auto object-contain"
                        loading="lazy">
                </div>
            </div>

            {{-- Deck Legend --}}
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-wrap items-center justify-center gap-4 text-xs md:text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-[#017249] "></span>
                        <span class="text-gray-600">Deck 1, 2, 8, 9 — Tent 4.2</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-[#0b5a3e] "></span>
                        <span class="text-gray-600">Deck 3, 4, 5, 6, 7 — Tent 4.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

