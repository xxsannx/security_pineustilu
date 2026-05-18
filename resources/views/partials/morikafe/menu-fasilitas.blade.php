<section class="bg-white py-8 sm:py-10 md:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-8 sm:space-y-10 md:space-y-12">
        {{-- Menu Section --}}
        <div data-aos="fade-up" data-aos-duration="800">
            <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#017249] uppercase text-center mb-4 sm:mb-6 md:mb-8 tracking-wider"
                style="font-family: 'Bizon', sans-serif;">MENU</h3>

            <div class="relative rounded-xl sm:rounded-2xl overflow-hidden border-2 sm:border-4 border-[#0b5a3e] shadow-lg">
                <iframe src="https://drive.google.com/file/d/1Dr8EPHERgTZMYIp0581AtYuIdKhyiooD/preview"
                    class="w-full h-[500px] sm:h-[600px] md:h-[720px] lg:h-[820px] xl:h-[900px]" frameborder="0" allow="autoplay; encrypted-media"
                    loading="lazy"></iframe>

                <a href="https://drive.google.com/file/d/1Dr8EPHERgTZMYIp0581AtYuIdKhyiooD/view" target="_blank"
                    rel="noopener"
                    class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-white/90 text-[#0b5a3e] hover:bg-white px-2 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm md:text-base lg:text-lg font-medium flex items-center gap-1 sm:gap-2 shadow-md">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Open in Drive
                </a>
            </div>
        </div>

        {{-- Facilities Section --}}
        <div data-aos="fade-up" data-aos-duration="800">
            <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#017249] uppercase text-center mb-4 sm:mb-6 md:mb-8 tracking-wider"
                style="font-family: 'Bizon', sans-serif;">FACILITIES</h3>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
                @for($i = 1; $i <= 6; $i++)
                    <div class="rounded-xl sm:rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('images/morikafe-galeri/fasilitas' . $i . '.jpg') }}" alt="fasilitas {{$i}}"
                            class="w-full h-36 sm:h-44 md:h-56 lg:h-64 xl:h-72 object-cover rounded-xl sm:rounded-2xl pointer-events-none select-none" loading="lazy" decoding="async">
                    </div>
                @endfor
            </div>
        </div>
    </div>
</section>
