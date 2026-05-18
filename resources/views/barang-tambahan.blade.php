@extends('layouts.app')

@section('title', 'Additional Items - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24')

@section('content')
    @php
        // Combine all item groups into a single flat collection
        $allItems = collect([])
            ->merge(collect($perlengkapan ?? []))
            ->merge(collect($daging ?? []))
            ->merge(collect($saus ?? []))
            ->sortBy(fn($item) => strtolower(is_array($item) ? $item['name'] ?? '' : $item->name ?? ''))
            ->values();
    @endphp
    <div class="w-full max-w-screen-xl mx-auto px-6">
        <div class="text-center mt-4 mb-6 md:mb-8" data-aos="fade-up" data-aos-duration="600">
            <h2 class="text-3xl md:text-4xl font-bold text-[#017249] mb-3" style="font-family: 'Bizon', sans-serif;"
                data-aos="fade-down" data-aos-duration="600" data-aos-delay="100">ADDITIONAL ITEMS</h2>
            <p class="text-gray-600 text-lg md:text-lg max-w-6xl mx-auto" data-aos="fade-up" data-aos-duration="600"
                data-aos-delay="200">
                Complete your camping experience with quality equipment and additional food for maximum comfort.
            </p>
        </div>

        {{-- Cards Section --}}
        <div class="max-w-5xl mx-auto">
            @if ($allItems->isEmpty())
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#017249] mb-2">No Additional Items Yet</h3>
                    <p class="text-gray-500">Additional items will be available soon.</p>
                </div>
            @else
                {{-- Single Card for All Items --}}
                <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-100 hover:border-[#017249]/30 transition-all duration-300 overflow-hidden"
                    data-aos="fade-up" data-aos-duration="600">
                    {{-- Card Header --}}
                    <div class="bg-gradient-to-r from-[#017249] to-[#015a3a] px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white tracking-wide"
                                style="font-family: 'Bizon', sans-serif;">Additional Items</h3>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5 space-y-1">
                        @foreach ($allItems as $item)
                            @php
                                $itemName = is_array($item) ? $item['name'] ?? '' : $item->name ?? '';
                                $itemDesc = is_array($item) ? $item['description'] ?? null : $item->description ?? null;
                                $itemPriceDisplay = is_array($item)
                                    ? $item['price_display'] ?? null
                                    : $item->price_display ?? null;
                            @endphp
                            <div
                                class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-[#017249]/5 transition-all duration-200">
                                <div class="flex-1">
                                    <div class="font-semibold text-[#0b5a3e] text-lg md:text-lg">{{ $itemName }}</div>
                                    @if ($itemDesc)
                                        <div class="text-base text-gray-500 mt-0.5">({{ $itemDesc }})</div>
                                    @endif
                                </div>
                                <div
                                    class="font-bold text-[#017249] text-sm md:text-base bg-[#017249]/10 px-3 py-1.5 rounded-full whitespace-nowrap">
                                    {{ $itemPriceDisplay ?? '-' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Info Note --}}
        <div class="max-w-5xl mx-auto mt-10" data-aos="fade-up" data-aos-duration="600">
            <div class="bg-white rounded-2xl shadow-md border border-[#017249]/10 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-[#017249]/10 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-[#017249] mb-1">Ordering Information</h4>
                        <p class="text-base text-gray-600 leading-relaxed">
                            To order additional items, please contact our admin via WhatsApp or inform us during
                            the reservation process.
                            Orders can be placed at least 1 day before check-in.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-reservation-cta />
@endsection

@push('scripts')
    @vite('resources/js/pages/barang-tambahan.js')
@endpush
