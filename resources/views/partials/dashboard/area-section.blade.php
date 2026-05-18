<!-- Area Cards Section -->
<section class="pt-8 md:pt-8 pb-12 md:pb-10 bg-white overflow-hidden w-full">
    <div class="max-w-6xl mx-auto px-6 w-full">
        <!-- Section Header -->
        <div class="text-center mb-6 md:mb-8" data-aos="fade-up">
            <h2 class="text-3xl md:text-5xl font-bold text-[#017249] mb-3" style="font-family: 'Bizon', sans-serif;">AREA</h2>
            <p class=" text-base md:text-lg max-w-xl mx-auto">
                The 4 camping areas located on the riverbank
            </p>
        </div>

        <!-- All Areas Grid - 3 columns -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 max-w-6xl mx-auto w-full"
            data-aos="fade-up" data-aos-delay="100">
            <!-- Pineus Tilu 1 -->
            <a href="{{ url('/area/pineus-tilu-1') }}"
                class="group block bg-[#017249] rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('images/area-galeri/pt-1/PT1.webp') }}" alt="Pineus Tilu 1" loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-4 text-center">
                    <span class="inline-block bg-white text-[#017249] px-4 py-2 rounded-full text-base font-bold mb-2" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 1</span>
                    <p class="text-white text-base leading-relaxed">The first area with the beautiful scenery</p>
                </div>
            </a>

            <!-- Pineus Tilu 2 -->
            <a href="{{ url('/area/pineus-tilu-2') }}"
                class="group block bg-[#017249] rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('images/area-galeri/pt-2/main.jpg') }}" alt="Pineus Tilu 2" loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-4 text-center">
                    <span class="inline-block bg-white text-[#017249] px-4 py-2 rounded-full text-base font-bold mb-2" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 2</span>
                    <p class="text-white text-base leading-relaxed">Calm & friendly area for peaceful time</p>
                </div>
            </a>

            <!-- Pineus Tilu 3 VIP -->
            <a href="{{ url('/area/pineus-tilu-3-vip') }}"
                class="group block bg-[#017249] rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('images/area-galeri/pt-3/main.jpg') }}" alt="Pineus Tilu 3 VIP"
                        loading="lazy" decoding="async"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-4 text-center">
                    <span class="inline-block bg-white text-[#017249] px-4 py-2 rounded-full text-base font-bold mb-2" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 3 VIP</span>
                    <p class="text-white text-base leading-relaxed">Strategic area with exclusive facilities</p>
                </div>
            </a>

            <!-- Pineus Tilu 4 -->
            <a href="{{ url('/area/pineus-tilu-4') }}"
                class="group block bg-[#017249] rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img loading="lazy" decoding="async"
                        src="{{ asset('images/area-galeri/pt-4/main.jpg') }}" alt="Pineus Tilu 4"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-4 text-center">
                    <span class="inline-block bg-white text-[#017249] px-4 py-2 rounded-full text-base font-bold mb-2" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 4</span>
                    <p class="text-white text-base leading-relaxed">The spacious area known for large group</p>
                </div>
            </a>

            <!-- Pineus Tilu Cabin VIP -->
            <a href="{{ url('/cabin/vip') }}"
                class="group block bg-[#B98C4F] rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img loading="lazy" decoding="async"
                        src="{{ asset('images/area-galeri/pt-cabin/main.webp') }}" alt="Pineus Tilu Cabin VIP"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-4 text-center">
                    <span class="inline-block bg-white text-[#B98C4F] px-4 py-2 rounded-full text-base font-bold mb-2" style="font-family: 'Poppins', sans-serif;">Pineus Tilu Cabin VIP</span>
                    <p class="text-white text-base leading-relaxed">Comfortable cabin for non-tent glamping</p>
                </div>
            </a>

            <!-- Pineus Tilu Cabin VVIP -->
            <a href="{{ url('/cabin/vvip') }}"
                class="group block bg-[#B98C4F] rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('images/area-galeri/pt-cabin-vvip/main.jpeg') }}"
                        alt="Pineus Tilu Cabin VVIP" loading="lazy" decoding="async"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-4 text-center">
                    <span class="inline-block bg-white text-[#B98C4F] px-4 py-2 rounded-full text-base font-bold mb-2" style="font-family: 'Poppins', sans-serif;">Pineus Tilu Cabin VVIP</span>
                    <p class="text-white text-base leading-relaxed">Exclusives & larger cabin with premium facilities</p>
                </div>
            </a>
        </div>
    </div>
</section>