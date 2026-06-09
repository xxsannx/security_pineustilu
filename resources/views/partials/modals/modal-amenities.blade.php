<!-- Modal Extra Items -->
<div id="amenitiesModal" class="fixed inset-0 backdrop-blur-sm bg-black/20 z-[9999] items-center justify-center p-4 hidden">
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl border-2 border-gray-100">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b-2 border-gray-100 p-6 md:p-8 flex justify-between items-center z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-br from-[#017249] to-[#015a3a] p-3 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800" style="font-family: 'Bizon', sans-serif;">Extra Items</h3>
                    <p class="text-gray-500 text-sm mt-1">Select add-ons to enhance your glamping experience</p>
                </div>
            </div>
            <button type="button" onclick="closeAmenitiesModal()" class="bg-gray-100 hover:bg-[#017249] hover:text-white text-gray-600 p-3 rounded-full transition-all duration-300 hover:rotate-90 hover:scale-110 shadow-md cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 md:p-8 space-y-6 overflow-y-auto max-h-[calc(90vh-180px)]">

            @php
                $itemsCollection = collect($items ?? []);
                $perlengkapan = $itemsCollection->filter(fn ($it) => ($it['group'] ?? null) === 'perlengkapan')->values();
                $dagingSaus = $itemsCollection->filter(fn ($it) => in_array(($it['group'] ?? null), ['daging', 'saus'], true))->values();

                $unitLabelMap = [
                    'pax' => ' / Pax',
                    'set' => ' / Set',
                    'kresek' => ' / Kresek',
                    'iket' => ' / Iket',
                    'pack' => '',
                    'botol' => '',
                    'bottle' => '',
                    'bag' => ' / Bag',
                    'bundle' => ' / Bundle',
                ];

                $colorByGroup = [
                    'perlengkapan' => ['from' => 'from-teal-500', 'to' => 'to-teal-600'],
                    'daging' => ['from' => 'from-red-500', 'to' => 'to-red-600'],
                    'saus' => ['from' => 'from-amber-500', 'to' => 'to-orange-600'],
                ];
            @endphp

            <!-- Category: Perlengkapan & Layanan -->
            <div class="mb-6">
                <h4 class="text-lg font-bold text-[#017249] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Equipment & Services
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse ($perlengkapan as $it)
                        @php
                            $g = $it['group'] ?? 'perlengkapan';
                            $c = $colorByGroup[$g] ?? ['from' => 'from-teal-500', 'to' => 'to-teal-600'];
                            $unitLabel = $unitLabelMap[$it['type'] ?? ''] ?? '';
                            $priceText = $it['price'] !== null ? ('Rp ' . number_format((float) $it['price'], 0, ',', '.') . $unitLabel) : 'Rp -';
                        @endphp
                        <div class="amenity-item bg-white rounded-xl p-5 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-gray-100 hover:border-[#017249]/40 flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">{{ $it['name'] ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $it['description'] ?? '' }}</div>
                                <div class="text-sm font-medium text-[#017249] mt-1">{{ $priceText }}</div>
                            </div>
                            <input form="{{ $formId ?? 'reservasiForm' }}" type="hidden" class="amenity-qty-input" name="amenities[{{ $it['id'] }}]" value="0" data-item-id="{{ $it['id'] }}" data-item-name="{{ $it['name'] ?? '' }}" data-price="{{ $it['price'] ?? 0 }}" data-type="{{ $it['type'] ?? '' }}">
                            <div class="flex items-center gap-1 bg-gray-100 rounded-2xl p-1">
                                <button type="button" class="amenity-qty-decrease w-8 h-8 flex items-center justify-center text-[#017249] hover:bg-[#017249] hover:text-white rounded-md transition-all duration-200 font-bold text-lg cursor-pointer" data-item-id="{{ $it['id'] }}">−</button>
                                <span class="amenity-qty-display w-8 text-center font-semibold text-gray-700 text-sm">0</span>
                                <button type="button" class="amenity-qty-increase w-8 h-8 flex items-center justify-center text-[#017249] hover:bg-[#017249] hover:text-white rounded-md transition-all duration-200 font-bold text-lg cursor-pointer" data-item-id="{{ $it['id'] }}">+</button>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No equipment items available.</div>
                    @endforelse
                </div>
            </div>

            <!-- Category: Daging & Saus -->
            <div class="mb-6">
                <h4 class="text-lg font-bold text-[#017249] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                    </svg>
                    Meat & BBQ Sauce
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse ($dagingSaus as $it)
                        @php
                            $g = $it['group'] ?? (($it['type'] ?? '') === 'botol' ? 'saus' : 'daging');
                            $c = $colorByGroup[$g] ?? ['from' => 'from-amber-500', 'to' => 'to-orange-600'];
                            $unitLabel = $unitLabelMap[$it['type'] ?? ''] ?? '';
                            $priceText = $it['price'] !== null ? ('Rp ' . number_format((float) $it['price'], 0, ',', '.') . $unitLabel) : 'Rp -';
                        @endphp
                        <div class="amenity-item bg-white rounded-xl p-5 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-gray-100 hover:border-[#017249]/40 flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">{{ $it['name'] ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $it['description'] ?? '' }}</div>
                                <div class="text-sm font-medium text-[#017249] mt-1">{{ $priceText }}</div>
                            </div>
                            <input form="{{ $formId ?? 'reservasiForm' }}" type="hidden" class="amenity-qty-input" name="amenities[{{ $it['id'] }}]" value="0" data-item-id="{{ $it['id'] }}" data-item-name="{{ $it['name'] ?? '' }}" data-price="{{ $it['price'] ?? 0 }}" data-type="{{ $it['type'] ?? '' }}">
                            <div class="flex items-center gap-1 bg-gray-100 rounded-2xl p-1">
                                <button type="button" class="amenity-qty-decrease w-8 h-8 flex items-center justify-center text-[#017249] hover:bg-[#017249] hover:text-white rounded-md transition-all duration-200 font-bold text-lg cursor-pointer" data-item-id="{{ $it['id'] }}">−</button>
                                <span class="amenity-qty-display w-8 text-center font-semibold text-gray-700 text-sm">0</span>
                                <button type="button" class="amenity-qty-increase w-8 h-8 flex items-center justify-center text-[#017249] hover:bg-[#017249] hover:text-white rounded-md transition-all duration-200 font-bold text-lg cursor-pointer" data-item-id="{{ $it['id'] }}">+</button>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No BBQ items available.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-white border-t-2 border-gray-100 px-6 md:px-8 py-4 md:py-5 flex items-center justify-between z-10 shadow-lg">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-[#017249] to-[#015a3a] text-white font-bold text-lg px-4 py-2 rounded-full shadow-md">
                    <span id="selectedCount">0</span>
                </div>
                <span class="text-sm text-gray-600 font-medium">items selected</span>
            </div>
            <button type="button" onclick="closeAmenitiesModal()"
                class="bg-gradient-to-r from-[#017249] to-[#015a3a] hover:from-[#015a3a] hover:to-[#017249] text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer">
                Save Selection
            </button>
        </div>
    </div>
</div>
