{{-- Gallery Section - Slider Style --}}
<section class="py-6 bg-white relative overflow-hidden">

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Main Card Container - Green Theme --}}
        <div
            class="bg-[#0b5a3e] border border-emerald-700/30 shadow-lg overflow-hidden text-white relative rounded-2xl">
            {{-- Decorative Elements Inside Card --}}
            {{-- Decorative blur elements --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-white blur-3xl translate-y-1/2 -translate-x-1/2"></div>
            </div>

            {{-- Card Inner Padding --}}
            <div class="px-6 py-10 md:px-10 md:py-12 relative z-10">
                {{-- Section Header --}}
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="600">
                    <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide" style="font-family:'Bizon',ui-sans-serif,system-ui;">GALLERY</h2>
                </div>

                @php
                    // Define fallback images based on area slug
                    $fallbackImages = match($areaSlug ?? 'pineus-tilu-1') {
                        'pineus-tilu-1' => [
                            'galeri1-pt1.jpeg',
                            'galeri2-pt1.jpeg',
                            'galeri3-pt1.jpg',
                            'galeri4-pt1.jpg',
                            'galeri5-pt1.jpeg',
                            'galeri6-pt1.jpg',
                        ],
                        'pineus-tilu-2' => [
                            'main.jpg',
                        ],
                        'pineus-tilu-3-vip' => [
                            'gallery1.png',
                            'gallery2.png',
                            'gallery3.png',
                            'gallery4.png',
                            'gallery5.png',
                            'gallery6.png',
                        ],
                        'pineus-tilu-4' => [
                            'main.jpg',
                        ],
                        default => [
                            'galeri1-pt1.jpeg',
                            'galeri2-pt1.jpeg',
                            'galeri3-pt1.jpg',
                            'galeri4-pt1.jpg',
                            'galeri5-pt1.jpeg',
                            'galeri6-pt1.jpg',
                        ],
                    };
                    
                    $galeriPath = match($areaSlug ?? 'pineus-tilu-1') {
                        'pineus-tilu-1' => 'pt-1',
                        'pineus-tilu-2' => 'pt-2',
                        'pineus-tilu-3-vip' => 'pt-3',
                        'pineus-tilu-4' => 'pt-4',
                        default => 'pt-1',
                    };
                    
                    // Use fallback images from public folder
                    $galeriItems = array_map(fn($img) => asset('images/area-galeri/' . $galeriPath . '/' . $img), $fallbackImages);
                    
                    $totalSlides = count($galeriItems);
                    $sliderId = 'area-gallery-slider-' . ($areaSlug ?? 'default');
                @endphp

                {{-- Slider Container --}}
                <div class="relative overflow-hidden rounded-2xl shadow-lg gallery-slider"
                    id="{{ $sliderId }}"
                    data-slider-id="{{ $sliderId }}"
                    data-total-slides="{{ $totalSlides }}"
                    data-theme="green"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    {{-- Slides Wrapper --}}
                    <div class="flex transition-transform duration-500 ease-in-out slider-wrapper">
                        @forelse($galeriItems as $index => $imgUrl)
                            <div class="min-w-full flex items-center justify-center bg-[#0b5a3e]">
                                <img src="{{ $imgUrl }}"
                                    alt="Gallery {{ $areaName ?? 'Pineus Tilu' }} {{ $index + 1 }}"
                                    loading="lazy" decoding="async"
                                    class="w-full h-auto max-h-[300px] md:max-h-[450px] lg:max-h-[550px] object-contain pointer-events-none select-none">
                            </div>
                        @empty
                            <div class="min-w-full flex items-center justify-center bg-[#0b5a3e] py-16">
                                <div class="text-center">
                                    <div class="w-24 h-24 mx-auto bg-white/10 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-white/60 text-lg">No gallery images available.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($totalSlides > 1)
                        {{-- Navigation Arrows --}}
                        <button type="button" class="slider-prev absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-all duration-300 z-10 cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button type="button" class="slider-next absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-all duration-300 z-10 cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        {{-- Slide Indicators --}}
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                            @for($i = 0; $i < $totalSlides; $i++)
                                <button type="button"
                                    class="slider-dot w-3 h-3 rounded-full transition-all duration-300 cursor-pointer {{ $i === 0 ? 'bg-white w-6' : 'bg-white/50' }}"
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

