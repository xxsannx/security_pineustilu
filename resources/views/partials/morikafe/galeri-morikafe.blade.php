<section class="bg-[#0b5a3e] py-8 sm:py-10 md:py-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-white">
        <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-center mb-4 sm:mb-6 md:mb-8 tracking-wider"
            style="font-family: 'Bizon', sans-serif;" data-aos="fade-down" data-aos-duration="600" data-aos-delay="100">GALLERY & MEETING ROOM</h3>

        <!-- Slider Container -->
        <div class="relative overflow-hidden rounded-xl sm:rounded-2xl shadow-lg" id="gallery-meeting-slider" data-total-slides="12">
            <!-- Slides Wrapper -->
            <div class="flex transition-transform duration-500 ease-in-out" id="slider-wrapper">
                <!-- Gallery Images -->
                @for($i = 1; $i <= 6; $i++)
                    <div class="min-w-full flex items-center justify-center bg-[#0b5a3e]">
                        <img src="{{ asset('images/morikafe-galeri/galeri' . $i . '.jpg') }}" alt="galeri {{$i}}"
                            loading="lazy" decoding="async"
                            class="w-full h-auto max-h-[220px] sm:max-h-[300px] md:max-h-[450px] lg:max-h-[550px] object-contain pointer-events-none select-none">
                    </div>
                @endfor
                <!-- Meeting Room Images -->
                @for($i = 1; $i <= 6; $i++)
                    <div class="min-w-full flex items-center justify-center bg-[#0b5a3e]">
                        <img src="{{ asset('images/morikafe-galeri/meeting' . $i . '.jpg') }}" alt="meeting {{$i}}"
                            loading="lazy" decoding="async"
                            class="w-full h-auto max-h-[220px] sm:max-h-[300px] md:max-h-[450px] lg:max-h-[550px] object-contain pointer-events-none select-none">
                    </div>
                @endfor
            </div>

            <!-- Navigation Arrows -->
            <button type="button" class="morikafe-prev absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 sm:p-3 rounded-full transition-all duration-300 z-10 cursor-pointer">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button type="button" class="morikafe-next absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 sm:p-3 rounded-full transition-all duration-300 z-10 cursor-pointer">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Slide Indicators -->
            <div class="absolute bottom-2 sm:bottom-4 left-1/2 -translate-x-1/2 flex gap-1 sm:gap-2 z-10">
                @for($i = 0; $i < 12; $i++)
                    <button type="button"
                        class="morikafe-dot w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all duration-300 cursor-pointer {{ $i === 0 ? 'bg-white w-4 sm:w-6' : 'bg-white/50' }}"
                        data-index="{{ $i }}"></button>
                @endfor
            </div>
        </div>

        <!-- Meeting Room Note -->
        <div class="bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 border border-white/20 mt-4 sm:mt-6 md:mt-8" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <p class="text-xs sm:text-sm md:text-base lg:text-lg text-white">
                <span class="font-extrabold text-amber-300">Note :</span>
                <span class="block mt-1 sm:mt-2 leading-relaxed">
                    Meeting room with a maximum capacity of 50 people is free for camping guests. For non-camping guests, the rate is
                    <strong class="font-extrabold text-amber-300">IDR 200,000/hour</strong> or
                    <strong class="font-extrabold text-amber-300">IDR 750,000/6 hours</strong>
                    (excluding food &amp; beverages). For reservations, please contact admin or make a reservation directly
                    on-site.
                </span>
            </p>
        </div>
    </div>
</section>
