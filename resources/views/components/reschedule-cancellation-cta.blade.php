<!-- Reschedule & Cancellation Call to Action Section -->
@props(['noMargin' => false])
@php
$bgImage = asset('images/cta/RNC_BG.jpg.jpeg');
$marginClass = $noMargin ? '' : 'my-4 sm:my-6 md:my-8';
@endphp
<section class="relative {{ $marginClass }} py-10 sm:py-14 md:py-20 lg:py-24 px-3 sm:px-4 overflow-hidden" data-aos="fade-up" data-aos-duration="800">
    <!-- Background Image with Dark Green-Black Overlay -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(rgba(0, 40, 25, 0.7), rgba(0, 30, 20, 0.75)), url('{{ $bgImage }}');"></div>

    <!-- Content -->
    <div class="relative z-10 max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-6 md:gap-8">
            <!-- Left Content -->
            <div class="text-center md:text-left flex-1">
                <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-2 sm:mb-3" style="font-family: 'Bizon', sans-serif;" data-aos="fade-up" data-aos-duration="800">
                    Need to Reschedule or Cancel?
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-white/90" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
                    For easy reschedule or cancel your reservation
                </p>
            </div>

            <!-- Right Buttons -->
            <div class="flex flex-row gap-3 sm:gap-3 md:gap-4 flex-shrink-0 w-full md:w-auto justify-center md:justify-end" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLScFQ12XFWnqwS-pMvwTI1iRyWzlWjNQHOkUfpfN6yALiV-0MQ/viewform" target="_blank" class="group inline-flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2 bg-white hover:bg-gray-50 text-[#017249] px-4 py-2.5 sm:px-5 sm:py-2.5 md:px-8 md:py-3.5 rounded-full font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 text-sm sm:text-sm md:text-lg whitespace-nowrap">
                    <svg class="w-4 h-4 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Reschedule
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLScFQ12XFWnqwS-pMvwTI1iRyWzlWjNQHOkUfpfN6yALiV-0MQ/viewform" target="_blank" class="group inline-flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 sm:px-5 sm:py-2.5 md:px-8 md:py-3.5 rounded-full font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 text-sm sm:text-sm md:text-lg whitespace-nowrap">
                    <svg class="w-4 h-4 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
            </div>
        </div>
    </div>
</section>