{{-- Fasilitas Section - VIP Gold Theme --}}
<section class="pt-6 pb-1 bg-white relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-6 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-14" data-aos="fade-up" data-aos-duration="600">
            <h2 class="text-2xl md:text-4xl font-extrabold text-[#9A7540] tracking-wide"
                style="font-family:'Bizon',ui-sans-serif,system-ui;">FACILITIES</h2>
        </div>

        {{-- Facility Accordions --}}
        @php
            $facilitySource = $facilities ?? collect();
            $fasilitasPribadi = $facilitySource->where('type', 'private');
            $fasilitasUmum = $facilitySource->where('type', 'shared');

            if ($fasilitasPribadi->isEmpty() && $fasilitasUmum->isEmpty()) {
                $fasilitasPribadi = $facilitySource;
                $fasilitasUmum = collect();
            }
        @endphp

        <div class="grid grid-cols-1 gap-6 mb-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            {{-- Private Facilities --}}
            <div class="group flex flex-col">
                <button type="button"
                    class="ft-toggle w-full rounded-2xl text-left flex items-center justify-between px-5 py-4 bg-white border-2 border-[#B98C4F]/20 shadow-md hover:border-[#B98C4F]/40 hover:shadow-lg transition-all duration-300 cursor-pointer"
                    data-target="fac-pri" aria-expanded="false">
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-12 bg-gradient-to-br from-[#9A7540] to-[#7D5E33] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <div>
                            <span class="font-bold text-[#9A7540] text-lg">Private Facilities</span>
                            <p class="text-base mt-0.5">Available in each cabin</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-[#B98C4F]/60 ft-icon transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div id="fac-pri" class="ft-panel mt-4 w-full bg-white border border-[#B98C4F]/10 rounded-2xl p-6 hidden shadow-inner">
                    @php
                        $groupedFacilities = $fasilitasPribadi->groupBy('description');
                    @endphp

                    @if($groupedFacilities->isNotEmpty())
                        {{-- Cabin: Display with subheadings for all groups --}}
                        @foreach($groupedFacilities as $groupName => $facilities)
                            @if($groupName)
                                <div class="mb-6 last:mb-0">
                                    <h4 class="text-[#9A7540] font-bold text-base mb-4 pb-2 border-b border-[#B98C4F]/20">
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
                                                    <span class="w-10 h-10 rounded-full flex items-center justify-center bg-[#E8CFA0]">
                                                        <svg class="w-4 h-4 text-[#9A7540]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        {{-- Default: No grouping --}}
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($fasilitasPribadi as $fasilitas)
                                <li class="flex items-center gap-3 p-3">
                                    @if ($fasilitas->icon)
                                        <span class="w-10 h-10 flex items-center justify-center">
                                            <img src="{{ asset($fasilitas->icon) }}" alt="{{ $fasilitas->name }}" class="w-10 h-10 object-contain">
                                        </span>
                                    @else
                                        <span class="w-10 h-10 rounded-full flex items-center justify-center bg-[#E8CFA0]">
                                            <svg class="w-4 h-4 text-[#9A7540]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <li class="text-gray-400 italic">No private facility data available.</li>
                            @endforelse
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Fasilitas Umum --}}
            <div class="group flex flex-col">
                <button type="button"
                    class="ft-toggle w-full rounded-2xl text-left flex items-center justify-between px-5 py-4 bg-white border-2 border-[#B98C4F]/20 shadow-md hover:border-[#B98C4F]/40 hover:shadow-lg transition-all duration-300 cursor-pointer"
                    data-target="fac-gen" aria-expanded="false">
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-12 bg-gradient-to-br from-[#9A7540] to-[#7D5E33] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        <div>
                            <span class="font-bold text-[#9A7540] text-lg">Public Facilities</span>
                            <p class="text-base mt-0.5">Available for all guests</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-[#B98C4F]/60 ft-icon transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div id="fac-gen" class="ft-panel mt-4 w-full bg-white border border-[#B98C4F]/10 rounded-2xl p-6 hidden shadow-inner">
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($fasilitasUmum as $fasilitas)
                            <li class="flex items-center gap-3 p-3">
                                @if ($fasilitas->icon)
                                    <span class="w-10 h-10 flex items-center justify-center">
                                        <img src="{{ asset($fasilitas->icon) }}" alt="{{ $fasilitas->name }}" class="w-10 h-10 object-contain">
                                    </span>
                                @else
                                    <span class="w-10 h-10 rounded-full flex items-center justify-center bg-[#E8CFA0]">
                                        <svg class="w-4 h-4 text-[#9A7540]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <li class="text-gray-400 italic">No public facility data available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
