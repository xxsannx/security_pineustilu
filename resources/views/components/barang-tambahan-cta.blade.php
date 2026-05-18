{{-- Additional Items CTA --}}
<section class="pt-1 pb-6 bg-white">
<div class="max-w-6xl mx-auto px-6 relative z-10">
<div class="bg-[#0b5a3e] p-3 sm:p-4 md:p-6 shadow-md rounded-xl sm:rounded-2xl" data-aos="fade-up"
    data-aos-duration="600" data-aos-delay="300">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-3 sm:gap-4 flex-1 w-full md:w-auto">
            <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0 rounded-xl sm:rounded-2xl">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-sm sm:text-base md:text-lg font-bold text-white" style="font-family:'Bizon',ui-sans-serif,system-ui;">Additional Items</h4>
                <p class="text-white/80 text-xs sm:text-sm leading-relaxed">
                    For more information about purchasing additional items, please click the "details" button.
                </p>
            </div>
        </div>
        <a href="{{ route('barang-tambahan') }}"
            class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-5 md:px-6 py-2.5 sm:py-3 bg-white text-[#017249] font-semibold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 rounded-xl sm:rounded-2xl text-sm sm:text-base md:text-base w-full md:w-auto md:flex-shrink-0">
            <span>Details</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>
    </div>
</div>
</div>
</section>
