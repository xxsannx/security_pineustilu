{{-- Parking Area Section --}}
<section class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden"
    data-aos="fade-up"
    data-aos-duration="800"
    aria-labelledby="parkir-heading">
    <div class="p-4 sm:p-6 md:p-8 lg:p-10">
        <x-section-heading
            id="parkir-heading"
            title="PARKING AREA"
            titleClass="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#017249] tracking-wider" />

        @php
        // Parking rates data - single source of truth
        $parkingRates = [
        'menginap' => [
        'title' => 'Overnight Parking',
        'unit' => 'night',
        'items' => [
        ['vehicle' => 'Car & Minibus', 'price' => 'IDR 40,000'],
        ['vehicle' => 'Medium Bus', 'price' => 'IDR 120,000'],
        ['vehicle' => 'Large Bus', 'price' => 'IDR 160,000'],
        ['vehicle' => 'Motorcycle', 'price' => 'IDR 10,000'],
        ['vehicle' => 'Bicycle', 'price' => 'IDR 2,000'],
        ],
        ],
        'transit' => [
        'title' => 'Transit Parking',
        'unit' => 'day',
        'items' => [
        ['vehicle' => 'Car & Minibus', 'price' => 'IDR 10,000'],
        ['vehicle' => 'Medium Bus', 'price' => 'IDR 30,000'],
        ['vehicle' => 'Large Bus', 'price' => 'IDR 50,000'],
        ['vehicle' => 'Motorcycle', 'price' => 'IDR 5,000'],
        ['vehicle' => 'Bicycle', 'price' => 'IDR 2,000'],
        ],
        ],
        ];
        @endphp

        {{-- Content Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 md:gap-8"
            style="font-family: 'Poppins', sans-serif;">

            {{-- Image & Notes - Left Side --}}
            <div data-aos="fade-right" data-aos-duration="800">
                <div class="flex flex-col h-full gap-4 sm:gap-6">
                    {{-- Image --}}
                    <div class="rounded-xl sm:rounded-2xl overflow-hidden shadow-lg border-2 border-gray-100 flex-1">
                        <img src="{{ asset('images/pedoman-galeri/tempatparkir.jpg') }}" alt="Parking Area" class="w-full h-full object-cover" loading="lazy">
                    </div>

                    {{-- Notes --}}
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg sm:rounded-xl p-3 sm:p-4 border-2 border-amber-200">
                        <p class="flex items-start gap-2 sm:gap-3 text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>
                                <strong class="text-amber-900">Note:</strong> Pineus Tilu does not manage parking fees, therefore payment must be made directly at the location to the parking management.
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Parking Info - Right Side --}}
            <div data-aos="fade-left" data-aos-duration="800">
                <div class="flex flex-col gap-4 sm:gap-6">
                    {{-- Parkir Menginap --}}
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border-2 border-gray-100">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-[#017249] mb-3 sm:mb-5 flex items-center gap-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.5 3C8.257 3 4 7.03 4 12c0 4.97 4.257 9 9.5 9 5.244 0 9.5-4.03 9.5-9 0-4.97-4.256-9-9.5-9zm2.334 9.27h-1.417v4.482h-1.834V12.27H11.17V10.73h4.664v1.54z" />
                            </svg>
                            {{ $parkingRates['menginap']['title'] }}
                        </h3>
                        <ul class="space-y-3 sm:space-y-4">
                            @foreach($parkingRates['menginap']['items'] as $item)
                            <li class="flex items-start gap-2 sm:gap-3">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-[#017249] mt-1.5 sm:mt-2"></div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">{{ $item['vehicle'] }}</span>
                                    <span class="text-xs sm:text-sm md:text-base lg:text-lg font-bold text-[#017249]">
                                        {{ $item['price'] }}
                                        <span class="text-xs sm:text-sm md:text-base lg:text-lg font-normal text-gray-600">/{{ $parkingRates['menginap']['unit'] }}</span>
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Parkir Transit --}}
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border-2 border-gray-100">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-[#017249] mb-3 sm:mb-5 flex items-center gap-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $parkingRates['transit']['title'] }}
                        </h3>
                        <ul class="space-y-3 sm:space-y-4">
                            @foreach($parkingRates['transit']['items'] as $item)
                            <li class="flex items-start gap-2 sm:gap-3">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-[#017249] mt-1.5 sm:mt-2"></div>
                                <div class="flex-1 flex items-center justify-between">
                                    <span class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">{{ $item['vehicle'] }}</span>
                                    <span class="text-xs sm:text-sm md:text-base lg:text-lg font-bold text-[#017249]">
                                        {{ $item['price'] }}
                                        <span class="text-xs sm:text-sm md:text-base lg:text-lg font-normal text-gray-600">/{{ $parkingRates['transit']['unit'] }}</span>
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>