<section class="relative w-full -mt-24 min-h-screen flex items-start pt-8 bg-[#1a1a1a] overflow-hidden">

    {{-- Image Slider Background --}}
    <div class="absolute inset-0" id="morikafe-slider">
        <div class="slider-image absolute inset-0 transition-opacity duration-1000" style="opacity: 1;">
            <img src="{{ asset('images/morikafe-galeri/morikafe1.jpg') }}" alt="Morikafe 1"
                class="w-full h-full object-cover">
        </div>
        <div class="slider-image absolute inset-0 transition-opacity duration-1000" style="opacity: 0;">
            <img src="{{ asset('images/morikafe-galeri/morikafe2.jpg') }}" alt="Morikafe 2"
                class="w-full h-full object-cover">
        </div>
    </div>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40"></div>

    {{-- River Wave Divider at bottom - synced with navbar via JS --}}
    <div id="waveDividerMorikafe"
        class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10 transform transition-all duration-300 ease-out translate-y-full opacity-0">
        <svg class="relative block w-full h-[60px] md:h-[80px]" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path
                d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V120H0Z"
                opacity=".2" class="fill-white"></path>
            <path
                d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V120H0Z"
                opacity=".2" class="fill-white"></path>
            <path
                d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V120H0Z"
                class="fill-white"></path>
        </svg>
    </div>

    {{-- Hero Content --}}
    <div class="relative z-10 w-full">
        <div class="max-w-6xl mx-auto px-6 overflow-hidden">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                {{-- Logo Morikafe --}}
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/morikafe-galeri/Morikafe_Logo_White.svg') }}" alt="Morikafe Logo"
                        class="h-24 sm:h-32 md:h-44 w-auto">
                </div>

                {{-- Operational Hours Info --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 md:gap-8">
                    <div class="flex items-center gap-2 text-white">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11a3 3 0 110-6 3 3 0 010 6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.5 11c0 7-7.5 11-7.5 11S4.5 18 4.5 11a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span class="text-xs sm:text-sm md:text-base font-normal">Pangalengan</span>
                    </div>
                    <div class="flex items-center gap-2 text-white">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs sm:text-sm md:text-base font-normal">Operating Hours 07.00 - 21.00</span>
                    </div>
                    <div class="flex items-center gap-2 text-white">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs sm:text-sm md:text-base font-normal">Close Order 20.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slider Indicators --}}
    <div class="absolute bottom-24 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
        <button type="button" onclick="setMorikafeSlide(0)"
            class="morikafe-indicator w-4 h-2 rounded-full bg-white transition-all duration-300 cursor-pointer"
            data-index="0"></button>
        <button type="button" onclick="setMorikafeSlide(1)"
            class="morikafe-indicator w-2 h-2 rounded-full bg-white/50 transition-all duration-300 cursor-pointer"
            data-index="1"></button>
    </div>
</section>
