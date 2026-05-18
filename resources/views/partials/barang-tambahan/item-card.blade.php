@php
    // Define type configurations
    $typeConfig = [
        'perlengkapan' => [
            'title' => 'Equipment & Services',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        ],
        'makanan' => [
            'title' => 'Food & Beverages',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>',
        ],
        'daging' => [
            'title' => 'Meat & BBQ',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>',
        ],
        'saus' => [
            'title' => 'Sauce & Seasoning',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>',
        ],
        'sewa' => [
            'title' => 'Equipment Rental',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
        ],
        'layanan' => [
            'title' => 'Additional Services',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
        ],
        'lainnya' => [
            'title' => 'Others',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>',
        ],
    ];

    $cardType = $type ?? 'lainnya';
    $config = $typeConfig[$cardType] ?? $typeConfig['lainnya'];
@endphp

<div class="extras-card bg-white rounded-2xl shadow-lg border-2 border-gray-100 hover:border-[#017249]/30 transition-all duration-300 overflow-hidden" data-aos="fade-up" data-aos-duration="600" data-card-category="{{ $cardType }}">
    {{-- Card Header --}}
    <div class="bg-gradient-to-r from-[#017249] to-[#015a3a] px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $config['icon'] !!}
                </svg>
            </div>
            <h3 class="extras-title text-xl font-bold text-white tracking-wide">{{ $config['title'] }}</h3>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="extras-list p-5 space-y-1">
        @forelse($items as $item)
            @php
                // Handle both array and object formats
                $itemName = is_array($item) ? ($item['name'] ?? '') : ($item->name ?? '');
                $itemDesc = is_array($item) ? ($item['description'] ?? null) : ($item->description ?? null);
                $itemPrice = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
            @endphp
            <div class="extras-row flex items-center justify-between py-3 px-4 rounded-xl hover:bg-[#017249]/5 transition-all duration-200">
                <div class="extras-info flex-1">
                    <div class="extras-name font-semibold text-[#0b5a3e] text-sm md:text-base">{{ $itemName }}</div>
                    @if($itemDesc)
                        <div class="extras-note text-xs text-gray-500 mt-0.5">({{ $itemDesc }})</div>
                    @endif
                </div>
                <div class="extras-price font-bold text-[#017249] text-sm md:text-base bg-[#017249]/10 px-3 py-1.5 rounded-full whitespace-nowrap">
                    @if($itemPrice > 0)
                        Rp {{ number_format($itemPrice, 0, ',', '.') }}
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400">
                <p>No items in this category yet.</p>
            </div>
        @endforelse
    </div>
</div>
