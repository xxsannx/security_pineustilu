{{-- Galeri Section - Slider Style - VIP Amber Theme --}}
<section class="py-6 bg-white relative overflow-hidden">

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Main Card Container - VIP Gold Theme --}}
        <div
            class="bg-gradient-to-br from-[#6B4E2A] via-[#7D5E33] to-[#6B4E2A] border border-[#9A7540]/30 shadow-lg overflow-hidden text-white relative rounded-2xl">
            {{-- Decorative Elements Inside Card --}}
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 right-0 w-96 h-96 bg-[#E8CFA0] blur-3xl -translate-y-1/2 translate-x-1/2">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-80 h-80 bg-[#D4AF6A] blur-3xl translate-y-1/2 -translate-x-1/2">
                </div>
            </div>

            {{-- Card Inner Padding --}}
            <div class="px-6 py-10 md:px-10 md:py-12 relative z-10">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="600">
                    <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide" style="font-family:'Bizon',ui-sans-serif,system-ui;">GALLERY</h2>
                </div>

                @php
                    // Define fallback images based on cabin tier
                    $fallbackImages = ($cabinTier ?? '') === 'VVIP'
                        ? ['livingroom.jpeg', 'bedroom.jpeg', 'toilet.jpeg', 'kitchen.jpeg', 'diningroom.jpeg', 'teras.jpeg']
                        : ['galericabinvip1.jpg', 'galericabinvip2.jpg', 'galericabinvip3.jpg', 'galericabinvip4.jpg', 'galericabinvip5.jpeg', 'galericabinvip6.jpg'];
                    
                    $galeriPath = ($cabinTier ?? '') === 'VVIP' ? 'pt-cabin-vvip' : 'pt-cabin';
                    
                    // Use fallback images from public folder
                    $galeriItems = array_map(fn($img) => asset('images/area-galeri/' . $galeriPath . '/' . $img), $fallbackImages);
                    
                    $totalSlides = count($galeriItems);
                    $sliderId = 'cabin-gallery-slider-' . ($galeriPath ?? 'default');
                @endphp

                {{-- Slider Container --}}
                <div class="relative overflow-hidden rounded-2xl shadow-lg gallery-slider" 
                    id="{{ $sliderId }}" 
                    data-slider-id="{{ $sliderId }}"
                    data-total-slides="{{ $totalSlides }}"
                    data-theme="amber"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    {{-- Slides Wrapper --}}
                    <div class="flex transition-transform duration-500 ease-in-out slider-wrapper">
                        @forelse($galeriItems as $index => $imgUrl)
                            <div class="min-w-full flex items-center justify-center bg-[#6B4E2A]">
                                <img src="{{ $imgUrl }}"
                                    alt="Galeri {{ $areaName ?? 'Pineus Tilu' }} {{ $index + 1 }}"
                                    loading="lazy" decoding="async"
                                    class="w-full h-auto max-h-[300px] md:max-h-[450px] lg:max-h-[550px] object-contain pointer-events-none select-none">
                            </div>
                        @empty
                            <div class="min-w-full flex items-center justify-center bg-[#6B4E2A] py-16">
                                <div class="text-center">
                                    <div class="w-24 h-24 mx-auto bg-[#D4AF6A]/10 flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-[#E8CFA0]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-[#E8CFA0]/60 text-lg">No gallery images yet.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($totalSlides > 1)
                        {{-- Navigation Arrows --}}
                        <button type="button" class="slider-prev absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-[#9A7540]/70 text-white p-3 rounded-full transition-all duration-300 z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button type="button" class="slider-next absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-[#9A7540]/70 text-white p-3 rounded-full transition-all duration-300 z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        {{-- Slide Indicators --}}
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                            @for($i = 0; $i < $totalSlides; $i++)
                                <button type="button" 
                                    class="slider-dot w-3 h-3 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-[#D4AF6A] w-6' : 'bg-[#D4AF6A]/50' }}"
                                    data-index="{{ $i }}"></button>
                            @endfor
                        </div>
                    @endif
                </div>
            </div>
            {{-- End Card Inner Padding --}}
        </div>
        {{-- End Main Card Container --}}
    </div>
</section>

