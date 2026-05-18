@props(['availability' => [], 'areaUnits' => [], 'items' => [], 'unitPrices' => [], 'highSeasonRanges' => [], 'unitExtraCharges' => []])
@php
    // Single static mapping (single source of truth)
    $maps = [
        'pineus-tilu-1' => 'pt-1.svg',
        'pineus-tilu-2' => 'pt-2.svg',
        'pineus-tilu-3-vip' => 'pt-3.svg',
        'pineus-tilu-4' => 'pt-4.svg',
        'pineus-tilu-cabin' => 'cabin-vip.svg',
        'pineus-tilu-cabin-vvip' => 'cabin-vvip.svg',
        // add more maps here if needed
    ];

    $defaultArea = 'pineus-tilu-1';

    // Build display names map (slug -> human friendly)
    $displayNames = [];
    foreach ($maps as $slug => $file) {
        // convert slug to title case: pineus-tilu-3-vip -> Pineus Tilu 3 (VIP)
        $parts = explode('-', $slug);
        $isVip = false;
        if (end($parts) === 'vip') { $isVip = true; array_pop($parts); }
        $title = implode(' ', array_map('ucfirst', $parts));
        if ($isVip) {
            // If last segment was numeric, append (VIP)
            if (preg_match('/\d+$/', $title)) $title = preg_replace('/\s*$/', '', $title) . ' (VIP)';
            else $title = trim($title . ' VIP');
        }
        $displayNames[$slug] = $title;
    }
@endphp

<div id="glampingMap" data-availability='{{ json_encode($availability ?? []) }}' data-area-units='{{ json_encode($areaUnits ?? []) }}' data-items='{{ json_encode($items ?? []) }}' data-unit-prices='{{ json_encode($unitPrices ?? []) }}' data-unit-extra-charges='{{ json_encode($unitExtraCharges ?? []) }}' data-high-season-ranges='{{ json_encode($highSeasonRanges ?? []) }}' data-default-area="{{ $defaultArea }}" data-area-names='{{ json_encode($displayNames) }}'
    {{ $attributes->merge(['class' => 'w-full h-full']) }}>
    @foreach ($maps as $area => $file)
        <div data-map="{{ $area }}" class="{{ $area === $defaultArea ? 'flex' : 'hidden' }} w-full h-full">
            <div
                class="w-full h-full bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center min-h-[280px]">
                <div class="w-full h-full p-3 flex items-center justify-center relative">
                    <div class="svg-wrap w-full h-full" data-src="{{ asset('images/reservation/' . $file) }}" aria-hidden="false"></div>

                    <!-- Pan/Zoom Controls (non-modal) -->
                    <div class="absolute right-3 top-3 flex flex-col gap-1.5 z-10">
                        <button type="button" data-zoom-in aria-label="Zoom in"
                            class="w-8 h-8 rounded-lg bg-white/90 hover:bg-white shadow border border-gray-200 text-gray-700 flex items-center justify-center cursor-pointer">
                            <span class="text-lg font-bold leading-none">+</span>
                        </button>
                        <button type="button" data-zoom-out aria-label="Zoom out"
                            class="w-8 h-8 rounded-lg bg-white/90 hover:bg-white shadow border border-gray-200 text-gray-700 flex items-center justify-center cursor-pointer">
                            <span class="text-xl font-bold leading-none">−</span>
                        </button>
                        <button type="button" data-zoom-reset aria-label="Reset zoom"
                            class="w-8 h-8 rounded-lg bg-white/90 hover:bg-white shadow border border-gray-200 text-gray-700 flex items-center justify-center cursor-pointer">
                            <span class="text-[9px] font-extrabold">RESET</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @vite('resources/js/pages/glamping-map.js')
</div>
