@php
    $images = $activity['images'] ?? [];
    $imageOne = $images[0] ?? asset('images/aktivitas-galeri/arungjeram1.jpg');
    $imageTwo = $images[1] ?? $imageOne;
    $variantOrder = $activity['variant_order'] ?? [];
    $variantNotes = $activity['variant_notes'] ?? [];
    $variantPrices = $activity['variant_prices'] ?? [];
    $mealPrices = $activity['meal_prices'] ?? [];
@endphp

<section class="w-full mb-6 md:mb-12">
    <div class="max-w-6xl mx-auto">
        <div class="group" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-[#017249] rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 border-2 border-gray-100 hover:border-[#017249]">
                <div class="grid grid-cols-1 md:grid-cols-2 md:min-h-[500px]">
                    <!-- Image Slider -->
                    <div class="relative overflow-hidden h-full" id="arungJeramSlider" data-aktivitas-slider="arung-jeram">
                        <div class="relative w-full h-full min-h-[200px] sm:min-h-[300px] md:min-h-full">
                            <img src="{{ $imageOne }}" alt="Arung Jeram 1"
                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-100"
                                id="arungJeramImg1" data-slider-image="0">
                            <img src="{{ $imageTwo }}" alt="Arung Jeram 2"
                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
                                id="arungJeramImg2" data-slider-image="1">
                            <!-- Slide Indicators -->
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                                <button type="button" class="w-4 h-2 rounded-full bg-white transition-all duration-300 cursor-pointer" id="arungJeramDot1" data-slider-dot="0" aria-label="Slide 1"></button>
                                <button type="button" class="w-2 h-2 rounded-full bg-white/50 transition-all duration-300 cursor-pointer" id="arungJeramDot2" data-slider-dot="1" aria-label="Slide 2"></button>
                            </div>
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="p-4 sm:p-6 md:p-8 flex flex-col justify-center">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-3 md:mb-4 transition-colors"
                            style="font-family: 'Bizon', sans-serif;">{{ $activity['title'] ?? 'RAFTING' }}</h3>

                        <h4 class="text-sm md:text-lg font-bold text-white mb-1 md:mb-2">PRICE</h4>
                        <ul class="text-xs sm:text-sm md:text-lg text-white leading-relaxed list-none space-y-1 mb-3 md:mb-4">
                            @foreach($variantOrder as $variantLabel)
                                <li>{{ $variantLabel }} = <strong>{{ $variantPrices[$variantLabel] ?? '-' }}</strong> <span class="text-white/80">{{ $variantNotes[$variantLabel] ?? '' }}</span></li>
                            @endforeach
                            <li class="text-white/80 mt-1">*Additional documentation: <strong>{{ $activity['base_price_text'] ?? 'Rp 100.000' }}</strong>/boat</li>
                        </ul>

                        <h4 class="text-sm md:text-lg font-bold text-white mb-1 md:mb-2">CAMPING + RAFTING</h4>
                        <p class="text-xs sm:text-sm md:text-lg text-white leading-relaxed mb-2 md:mb-3">{{ $activity['camping_package_text'] ?? 'Special package price for Pineus Tilu guests who also book rafting. Rafting discount Rp 50,000/boat. Requirements: Pineus Tilu Guest & Follow IG @boomrafting' }}</p>

                        <h4 class="text-sm md:text-lg font-bold text-white mb-1 md:mb-2">RAFTING + MEAL</h4>
                        <p class="text-xs sm:text-sm md:text-lg text-white leading-relaxed mb-2 md:mb-3">{{ $activity['meal_package_text'] ?? 'Rafting package + Complete Nasi Liwet, served in a pot. Discount Rp 30,000/pot.' }}</p>
                        <ul class="text-xs sm:text-sm md:text-lg text-white list-none space-y-1 mb-3 md:mb-4">
                            @foreach($mealPrices as $meal)
                                <li>{{ $meal['label'] ?? '' }}: <strong>{{ \App\Helpers\CurrencyHelper::formatRupiah($meal['price'] ?? null) }}</strong> {{ $meal['note'] ?? '' }}</li>
                            @endforeach
                        </ul>

                        <h4 class="text-sm md:text-lg font-bold text-white mb-1 md:mb-2">FACILITIES</h4>
                        <p class="text-xs sm:text-sm md:text-lg text-white leading-relaxed mb-2">{{ $activity['facilities_text'] ?? 'Guide & lifeguard, first aid kit/safety equipment, rinse area, insurance, local transportation & instructor, documentation (including photos and videos).' }}</p>

                        <div class="mt-3 md:mt-4 text-xs sm:text-sm md:text-lg text-white">
                            <p class="mb-1"><strong>DURATION:</strong> {{ $activity['duration_text'] ?? '5 Km (~90 minutes)' }}</p>
                            <p class="mb-1"><strong>AGE:</strong> {{ $activity['age_text'] ?? 'Min. 10 years old' }}</p>
                            <p class="mt-3">More details: <a href="{{ $activity['details_url'] ?? 'https://boomrafting.id' }}" class="text-white underline font-semibold hover:text-white/80" target="_blank" rel="noopener">{{ $activity['details_label'] ?? 'boomrafting.id' }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
