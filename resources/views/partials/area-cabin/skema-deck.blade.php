{{-- Deck Schema Section - Enhanced - VIP Gold Theme --}}
<section class="py-6" data-aos="fade-up" data-aos-duration="600">
    <div class="max-w-6xl mx-auto px-6">
        {{-- Intro Text --}}
        <div class="text-center mb-10" data-aos="fade-up" data-aos-delay="100">
            {{-- Description  --}}
            <p class="text-[#7D5E33] text-xl md:text-xl leading-relaxed max-w-6xl mx-auto">
                {{ $area->description ?? 'Experience the closest stay to nature at <strong class="text-[#9A7540]">Pineus Tilu Cabin</strong>, located right on the riverbank. Perfect for those who want to relax while enjoying the sound of flowing water and river panorama just steps away from your place.' }}
            </p>
        </div>

        {{-- Section Title --}}
        <div class="text-center mb-14" data-aos="fade-up" data-aos-delay="150">
            <h2 class="text-2xl md:text-4xl font-extrabold text-[#9A7540] tracking-wide" style="font-family:'Bizon',ui-sans-serif,system-ui;">DECK LAYOUT</h2>
        </div>

        {{-- Deck Schema Card --}}
        <div class="bg-white shadow-md border border-[#E8CFA0]/50 overflow-hidden hover:shadow-lg transition-shadow duration-500 max-w-6xl mx-auto rounded-2xl"
            data-aos="zoom-in" data-aos-delay="200">
            <div class="p-2">
                @php
                    $deckImage = ($cabinTier ?? 'VIP') === 'VVIP' ? 'ptcabinvvip_deck.svg' : 'ptcabinvip_deck.svg';
                    $deckTitle = $areaName ?? ('Pineus Tilu Cabin ' . ($cabinTier ?? 'VIP'));
                @endphp
                <div class="relative w-full bg-gradient-to-br from-[#F5EDD7] to-[#E8CFA0]/50 overflow-hidden">
                    <img src="{{ asset('images/deck/' . $deckImage) }}"
                        alt="Denah {{ $deckTitle }}"
                        class="w-full h-auto object-contain"
                        loading="lazy">
                </div>
            </div>

            {{-- Deck Legend --}}
            <div class="px-4 py-3 bg-[#F5EDD7] border-t border-[#E8CFA0]/50">
                <div class="flex flex-wrap items-center justify-center gap-4 text-xs md:text-sm">
                    @if(($cabinTier ?? 'VIP') === 'VVIP')
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-[#7D5E33]"></span>
                            <span class="text-gray-600">Cabin VVIP</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-[#9A7540]"></span>
                            <span class="text-gray-600">Cabin VIP</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
