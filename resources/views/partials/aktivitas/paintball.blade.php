@php
    $image = $activity['images'][0] ?? asset('images/aktivitas-galeri/paintball.jpg');
    $priceNoteTemplate = $activity['price_note_template'] ?? '(min :min pax)';
    $priceNote = str_replace(':min', (string) ($activity['min_participants'] ?? 10), $priceNoteTemplate);
@endphp

<div class="group h-full" data-aos="fade-up" data-aos-duration="800">
    <div class="bg-[#017249] rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 border-2 border-gray-100 hover:border-[#017249] h-full">
        <div class="grid grid-cols-1 md:grid-cols-2 h-full">
            <!-- Image -->
            <div class="relative overflow-hidden">
                <div class="h-full min-h-[200px] sm:min-h-[250px]">
                    <img src="{{ $image }}" alt="Paintball"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            </div>
            <!-- Text -->
            <div class="p-4 sm:p-5 md:p-6 flex flex-col justify-start">
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-white mb-2 sm:mb-3 transition-colors"
                    style="font-family: 'Bizon', sans-serif;">{{ $activity['title'] ?? 'PAINTBALL' }}</h3>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">PRICE</h4>
                <p class="text-xs sm:text-sm md:text-base text-white mb-2 sm:mb-3"><strong>{{ ($activity['base_price_text'] ?? 'Rp 80.000') . '/pax' }}</strong> <span class="text-white/80">{{ $priceNote }}</span></p>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">FACILITIES</h4>
                <p class="text-xs sm:text-sm md:text-base text-white leading-relaxed mb-2 sm:mb-3">{{ $activity['facilities_text'] ?? 'Uniform, protective vest, mask/goggles, paintball marker/gun, 30 bullets; including instructor, ticket, First Aid Kit / Safety equipment.' }}</p>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">DURATION</h4>
                <p class="text-xs sm:text-sm md:text-base text-white mb-2 sm:mb-3">{{ $activity['duration_text'] ?? 'Until bullets run out' }}</p>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">AGE</h4>
                <p class="text-xs sm:text-sm md:text-base text-white">{{ $activity['age_text'] ?? 'Min. 13 years old' }}</p>
            </div>
        </div>
    </div>
</div>
