@php
    $image = $activity['images'][0] ?? asset('images/aktivitas-galeri/funatv.jpg');
    $labels = $activity['variant_labels'] ?? [];
    $notes = $activity['variant_notes'] ?? [];
    $singleLabel = $labels['single'] ?? 'Single (1 pax)';
    $doubleLabel = $labels['double'] ?? 'Double (2 pax)';
    $singlePrice = $activity['variant_prices'][$singleLabel] ?? 'Rp 175.000';
    $doublePrice = $activity['variant_prices'][$doubleLabel] ?? 'Rp 250.000';
@endphp

<div class="group h-full" data-aos="fade-up" data-aos-duration="800">
    <div class="bg-[#017249] rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 border-2 border-gray-100 hover:border-[#017249] h-full">
        <div class="grid grid-cols-1 md:grid-cols-2 h-full">
            <!-- Image -->
            <div class="relative overflow-hidden">
                <div class="h-full min-h-[200px] sm:min-h-[250px]">
                    <img src="{{ $image }}" alt="Fun ATV"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            </div>
            <!-- Text -->
            <div class="p-4 sm:p-5 md:p-6 flex flex-col justify-start">
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-white mb-2 sm:mb-3 transition-colors"
                    style="font-family: 'Bizon', sans-serif;">{{ $activity['title'] ?? 'FUN ATV' }}</h3>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">PRICE</h4>
                <p class="text-xs sm:text-sm md:text-base text-white mb-2 sm:mb-3">
                    <strong>{{ $singlePrice }}</strong>/1 pax <span class="text-white/80">{{ $notes['single'] ?? '(one)' }}</span><br>
                    <strong>{{ $doublePrice }}</strong>/2 pax <span class="text-white/80">{{ $notes['double'] ?? '(two)' }}</span>
                </p>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">FACILITIES</h4>
                <p class="text-xs sm:text-sm md:text-base text-white leading-relaxed mb-2 sm:mb-3">{{ $activity['facilities_text'] ?? 'ATV unit, helmet, instructor, First Aid Kit / Safety equipment, insurance & ticket.' }}</p>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">DURATION</h4>
                <p class="text-xs sm:text-sm md:text-base text-white mb-2 sm:mb-3">{{ $activity['duration_text'] ?? '4 Km (~60 minutes)' }}</p>

                <h4 class="text-xs sm:text-sm md:text-base font-bold text-white mb-1">AGE</h4>
                <p class="text-xs sm:text-sm md:text-base text-white">{{ $activity['age_text'] ?? 'Min. 13 years old (as driver) / Min. 5 years old (as passenger)' }}</p>
            </div>
        </div>
    </div>
</div>
