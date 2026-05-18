{{-- Facilities Section --}}
<section class="pt-6 pb-1 bg-white relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-6 relative z-10">
        @php
            $tentDeckDescriptions = [
                'Tent 4.2' => 'Deck 1, 2, 8, 9',
                'Tent 4.0' => 'Deck 3, 4, 5, 6, 7',
                'Tent 6.3' => 'Deck 1, 2, 3, 4, 5',
                'Tent 5.2' => 'Deck 6, 7, 8, 9',
            ];

            if (isset($prices) && is_array($prices)) {
                $seasonOrder = ['weekday', 'weekend', 'high_season', 'ramadan_weekday', 'ramadan_weekend'];
                $priceRows = [];

                foreach ($seasonOrder as $seasonKey) {
                    if (!empty($prices[$seasonKey]) && is_array($prices[$seasonKey])) {
                        $priceRows = $prices[$seasonKey];
                        break;
                    }
                }

                foreach ($priceRows as $priceRow) {
                    $tentType = trim((string) ($priceRow['tent_type'] ?? ''));
                    $unitLabel = trim((string) ($priceRow['unit_names'] ?? ''));

                    if ($tentType !== '' && $unitLabel !== '') {
                        $tentDeckDescriptions[$tentType] = $unitLabel;
                    }
                }
            }

            $tentImageMap = [
                'Tent 4.2' => 'images/tent/4.2.jpg',
                'Tent 4.0' => 'images/tent/4.0.webp',
                'Tent 6.3' => 'images/tent/6.3.png',
                'Tent 5.2' => 'images/tent/5.2.png',
            ];

            $areaSlug = (string) ($area->slug ?? '');

            if ($areaSlug === 'pineus-tilu-3-vip') {
                $tentCardOrder = ['Tent 6.3', 'Tent 5.2'];
            } elseif ($areaSlug === 'pineus-tilu-4') {
                $tentCardOrder = ['Tent 4.2'];
            } else {
                $tentCardOrder = ['Tent 4.2', 'Tent 4.0'];
            }

            $facilityTentCards = array_values(array_filter(array_map(function ($tentName, $index) use ($tentDeckDescriptions, $tentImageMap) {
                $imagePath = $tentImageMap[$tentName] ?? null;
                if (!$imagePath) {
                    return null;
                }

                return [
                    'name' => $tentName,
                    'deck_text' => $tentDeckDescriptions[$tentName] ?? 'Not available in this area',
                    'image_path' => $imagePath,
                    'header_bg' => $index % 2 === 0 ? '#0b5a3e' : '#084d35',
                ];
            }, $tentCardOrder, array_keys($tentCardOrder))));

            $isSingleTentCard = count($facilityTentCards) === 1;
        @endphp

        {{-- Section Header --}}
        <div class="text-center mb-14" data-aos="fade-up" data-aos-duration="600">
            <h2 class="text-2xl md:text-4xl font-extrabold text-[#017249] tracking-wide"
                style="font-family:'Bizon',ui-sans-serif,system-ui;">FACILITIES</h2>
        </div>

        {{-- Tent Diagrams --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
            @foreach($facilityTentCards as $tentCard)
                <div class="group {{ $isSingleTentCard ? 'md:col-span-2 md:max-w-2xl md:mx-auto w-full' : '' }}">
                    <div class="bg-white shadow-md hover:shadow-lg transition-all duration-500 overflow-hidden border border-gray-100 rounded-2xl">
                        <div class="p-4" style="background-color: {{ $tentCard['header_bg'] }};">
                            <h4 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ $tentCard['name'] }}
                            </h4>
                            <p class="text-white/70 text-sm mt-1">{{ $tentCard['deck_text'] }}</p>
                        </div>
                        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100">
                            <img src="{{ asset($tentCard['image_path']) }}" alt="{{ $tentCard['name'] }} Diagram"
                                class="w-full h-auto object-contain p-2 transition-transform duration-500">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Facility Accordions --}}
        @php
            $fasilitasPribadi = $area->facilities->where('type', 'private') ?? collect();
            $fasilitasUmum = $area->facilities->where('type', 'shared') ?? collect();
        @endphp

        <div class="grid grid-cols-1 gap-6 mb-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            {{-- Private Facilities --}}
            <div class="group flex flex-col">
                <button type="button"
                    class="ft-toggle w-full rounded-2xl text-left flex items-center justify-between px-5 py-4 bg-white border-2 border-[#017249]/20 shadow-md hover:border-[#017249]/40 hover:shadow-lg transition-all duration-300 cursor-pointer"
                    data-target="fac-pri" aria-expanded="false">
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-12 bg-gradient-to-br from-[#017249] to-[#0b5a3e] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <div>
                            <span class="font-bold text-[#017249] text-lg">Private Facilities</span>
                            <p class="text-base mt-0.5">Available in each tent</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-[#017249]/60 ft-icon transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div id="fac-pri" class="ft-panel mt-4 w-full bg-white border border-[#017249]/10 rounded-2xl p-6 hidden shadow-inner">
                    @php
                        $isPT3VIP = isset($area) && $area->slug === 'pineus-tilu-3-vip';
                        $groupedFacilities = $isPT3VIP ? $fasilitasPribadi->groupBy('description') : collect();
                    @endphp

                    @if($isPT3VIP && $groupedFacilities->isNotEmpty())
                        {{-- PT3 VIP: Display with subheadings for all groups --}}
                        @foreach($groupedFacilities as $groupName => $facilities)
                            @if($groupName)
                                <div class="mb-6 last:mb-0">
                                    <h4 class="text-[#017249] font-bold text-base mb-4 pb-2 border-b border-[#017249]/20">
                                        {{ $groupName }}
                                    </h4>
                                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($facilities as $fasilitas)
                                            <li class="flex items-center gap-3 p-3">
                                                @if ($fasilitas->icon)
                                                    <span class="w-10 h-10 flex items-center justify-center">
                                                        <img src="{{ asset($fasilitas->icon) }}" alt="{{ $fasilitas->name }}" class="w-10 h-10 object-contain">
                                                    </span>
                                                @else
                                                    <span class="w-10 h-10 rounded-full flex items-center justify-center bg-[#017249]/10">
                                                        <svg class="w-4 h-4 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </span>
                                                @endif
                                                <span class="text-base text-gray-700 font-medium">
                                                    {{ $fasilitas->name }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                    @else
                        {{-- Default: No grouping for non-PT3 VIP areas --}}
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($fasilitasPribadi as $fasilitas)
                                <li class="flex items-center gap-3 p-3">
                                    @if ($fasilitas->icon)
                                        <span class="w-10 h-10 flex items-center justify-center">
                                            <img src="{{ asset($fasilitas->icon) }}" alt="{{ $fasilitas->name }}" class="w-10 h-10 object-contain">
                                        </span>
                                    @else
                                        <span class="w-10 h-10 rounded-full flex items-center justify-center bg-[#017249]/10">
                                            <svg class="w-4 h-4 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    @endif
                                    <span class="text-base text-gray-700 font-medium">
                                        {{ $fasilitas->name }}
                                        @if ($fasilitas->description)
                                            <br><span class="text-sm text-gray-400">({{ $fasilitas->description }})</span>
                                        @endif
                                    </span>
                                </li>
                            @empty
                                <li class="text-gray-400 italic">No private facilities data available.</li>
                            @endforelse
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Public Facilities --}}
            <div class="group flex flex-col">
                <button type="button"
                    class="ft-toggle w-full rounded-2xl text-left flex items-center justify-between px-5 py-4 bg-white border-2 border-[#017249]/20 shadow-md hover:border-[#017249]/40 hover:shadow-lg transition-all duration-300 cursor-pointer"
                    data-target="fac-gen" aria-expanded="false">
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-12 bg-gradient-to-br from-[#017249] to-[#0b5a3e] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        <div>
                            <span class="font-bold text-[#017249] text-lg">Public Facilities</span>
                            <p class="text-base  mt-0.5">Available for all guests</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-[#017249]/60 ft-icon transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div id="fac-gen" class="ft-panel mt-4 w-full bg-white border border-[#017249]/10 rounded-2xl p-6 hidden shadow-inner">
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($fasilitasUmum as $fasilitas)
                            <li class="flex items-center gap-3 p-3">
                                @if ($fasilitas->icon)
                                    <span class="w-10 h-10 flex items-center justify-center">
                                        <img src="{{ asset($fasilitas->icon) }}" alt="{{ $fasilitas->name }}" class="w-10 h-10 object-contain">
                                    </span>
                                @else
                                    <span class="w-10 h-10 rounded-full flex items-center justify-center bg-[#017249]/10">
                                        <svg class="w-4 h-4 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                @endif
                                <span class="text-base text-gray-700 font-medium">
                                    {{ $fasilitas->name }}
                                    @if ($fasilitas->description)
                                        <span class="text-xs text-gray-400">({{ $fasilitas->description }})</span>
                                    @endif
                                </span>
                            </li>
                        @empty
                            <li class="text-gray-400 italic">No public facilities data available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
