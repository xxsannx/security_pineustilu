<section class="relative w-full -mt-24 min-h-[100svh] md:min-h-screen flex items-start pt-8 bg-[#1a1a1a] overflow-hidden">

    <!-- Video Background -->
    <video id="heroVideo" autoplay loop playsinline
        class="absolute inset-0 w-full h-full object-cover opacity-100">
        <source src="{{ asset('video/loopingdashboard.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Sound Toggle Button -->
    <button id="soundToggle" type="button"
        class="absolute bottom-6 md:bottom-24 right-4 sm:right-6 z-30 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 shadow-lg cursor-pointer">
        <svg id="soundOff" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
        </svg>
        <svg id="soundOn" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
        </svg>
    </button>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- River Wave Divider at bottom - synced with navbar via JS -->
    <div id="waveDivider"
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

    <!-- Hero Content -->
    <div class="relative z-10 w-full">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 overflow-hidden">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <!-- Logo -->
                <div class="flex justify-center mb-2">
                    <img src="{{ asset('images/dashboard/logo.png') }}" alt="Pineus Tilu Logo"
                        class="h-24 md:h-32 w-auto brightness-0 invert" data-aos="fade-down" data-aos-duration="300"
                        data-aos-delay="100">
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-6 md:mb-8 leading-tight"
                    style="font-family: 'Bizon', sans-serif;" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">
                    An Unforgettable Natural Experience
                </h1>

                <div class="flex flex-row justify-center gap-2 sm:gap-3 md:gap-4 px-2 sm:px-0">
                    <a href="{{ url('/reservasi/glamping') }}"
                        class="inline-flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2
                               bg-[#017249] hover:bg-[#015a3a] 
                               text-white px-3 py-2.5 sm:px-5 sm:py-3 md:px-10 md:py-4 
                               rounded-full font-semibold text-xs sm:text-sm md:text-lg 
                               transition-all duration-300 shadow-lg 
                               hover:shadow-xl hover:scale-105 flex-1 sm:flex-none max-w-[180px] sm:max-w-none"
                        data-aos="zoom-in" data-aos-duration="400" data-aos-delay="300">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Reservation</span>
                    </a>
                    <a href="{{ url('/reservasi/glamping') }}"
                        class="inline-flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2
                               bg-white hover:bg-gray-100 
                               text-[#017249] px-3 py-2.5 sm:px-5 sm:py-3 md:px-10 md:py-4 
                               rounded-full font-semibold text-xs sm:text-sm md:text-lg 
                               transition-all duration-300 shadow-lg 
                               hover:shadow-xl hover:scale-105 flex-1 sm:flex-none max-w-[180px] sm:max-w-none"
                        data-aos="zoom-in" data-aos-duration="400" data-aos-delay="400">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span class="whitespace-nowrap">Availability</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
