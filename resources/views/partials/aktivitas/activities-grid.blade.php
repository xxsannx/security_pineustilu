<section class="w-full mb-6 sm:mb-8" data-aos="fade-up" data-aos-duration="800">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-center text-[#017249] tracking-wider mb-4 sm:mb-6 md:mb-8"
            style="font-family: 'Bizon', sans-serif;">ACTIVITIES AROUND PINEUS TILU</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            @foreach(($aroundCards ?? []) as $index => $card)
                <article class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl overflow-hidden transition-all duration-500 border-2 border-gray-100 hover:border-[#017249]" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <div class="w-full h-36 sm:h-40 md:h-40 lg:h-44 overflow-hidden">
                        <img src="{{ asset($card['image'] ?? '') }}" alt="{{ $card['title'] ?? 'Activity' }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-3 sm:p-4 md:p-5">
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-[#017249] mb-1 sm:mb-2">{{ $card['title'] ?? '' }}</h3>
                        <p class="text-xs sm:text-sm md:text-base text-gray-700 leading-relaxed">{{ $card['description'] ?? '' }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
