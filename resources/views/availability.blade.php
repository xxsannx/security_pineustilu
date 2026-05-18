@extends('layouts.app')

@section('title', 'Availability Table - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 w-full min-h-screen bg-gradient-to-br from-gray-50 to-white')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header -->
    <div class="text-center mb-6" data-aos="fade-up" data-aos-duration="600">
        <x-page-heading
            title="Availability Table"
            wrapperClass="text-center"
            titleClass="text-3xl md:text-4xl font-extrabold text-brand-primary tracking-wider mb-2"
            titleStyle="font-family: 'Bizon', sans-serif;" />
        <p class="text-gray-600 text-sm md:text-base font-medium" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">Real-time availability status for all glamping units</p>
    </div>

    <!-- Combined Card: Area Selector + Availability Table -->
    <div class="bg-white rounded-2xl shadow-2xl border-2 border-brand-primary/10 overflow-hidden" data-aos="fade-up" data-aos-duration="600">
        <!-- Area Selector Section -->
        <div class="p-6 bg-gradient-to-r from-white to-gray-50 border-b-2 border-gray-100" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
            <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Select Area</h3>
            <div id="areaSelector">
                @php
                    $areaBtnClass = 'justify-self-center border-0 px-2 sm:px-3 py-2 text-[#017249] font-extrabold cursor-pointer text-center rounded-xl transition duration-200 min-w-0 sm:min-w-[100px] w-full bg-[#e7f4ef] hover:bg-[#d9efe7] focus:outline-none focus:ring-2 focus:ring-[#017249]/40 aria-pressed:bg-[#017249] aria-pressed:text-white aria-pressed:shadow-[0_2px_8px_rgba(1,50,30,0.12)] flex flex-col items-center justify-center h-16 sm:h-[68px]';
                    $areaBtnCabinClass = 'justify-self-center border-0 px-2 sm:px-3 py-2 text-white font-extrabold cursor-pointer text-center rounded-xl transition duration-200 min-w-0 sm:min-w-[100px] w-full bg-[#B98C4F] hover:bg-[#A67E45] focus:outline-none focus:ring-2 focus:ring-[#B98C4F]/50 aria-pressed:bg-[#9C773E] aria-pressed:text-white aria-pressed:shadow-[0_2px_8px_rgba(185,140,79,0.35)] flex flex-col items-center justify-center h-16 sm:h-[68px]';
                @endphp
                
                <!-- Progress Track -->
                <div class="h-[6px] bg-[#e6f6ef] rounded-full relative" id="areaTrack" aria-hidden="true">
                    <div id="areaKnob"
                        class="w-5 h-5 rounded-full bg-brand-primary absolute top-1/2 -translate-y-1/2 transition-[left] duration-[320ms] shadow-[0_2px_6px_rgba(1,50,30,0.18)]"
                        style="left:0;"></div>
                </div>

                <!-- Area Buttons Grid -->
                <div id="areaItems"
                    class="grid grid-cols-3 sm:grid-cols-6 gap-1.5 sm:gap-2 mt-4 select-none w-full items-start"
                    aria-label="Select Area">
                    <button type="button"
                        class="{{ $areaBtnClass }}"
                        aria-pressed="true" 
                        data-area="pineus-tilu-1">
                        <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                        <div class="text-sm sm:text-sm">1</div>
                    </button>
                    <button type="button"
                        class="{{ $areaBtnClass }}"
                        aria-pressed="false" 
                        data-area="pineus-tilu-2">
                        <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                        <div class="text-sm sm:text-sm">2</div>
                    </button>
                    <button type="button"
                        class="{{ $areaBtnClass }}"
                        aria-pressed="false" 
                        data-area="pineus-tilu-3-vip">
                        <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                        <div class="text-sm sm:text-sm">3 <span class="text-sm sm:text-sm">VIP</span></div>
                    </button>
                    <button type="button"
                        class="{{ $areaBtnClass }}"
                        aria-pressed="false" 
                        data-area="pineus-tilu-4">
                        <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                        <div class="text-sm sm:text-sm">4</div>
                    </button>
                    <button type="button"
                        class="{{ $areaBtnCabinClass }}"
                        aria-pressed="false" 
                        data-area="pineus-tilu-cabin">
                        <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                        <span class="text-sm sm:text-sm">CABIN VIP</span>
                    </button>
                    <button type="button"
                        class="{{ $areaBtnCabinClass }}"
                        aria-pressed="false" 
                        data-area="pineus-tilu-cabin-vvip">
                        <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                        <div class="text-sm sm:text-sm">CABIN <span class="text-sm sm:text-sm">VVIP</span></div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Month & Year Selector -->
        <div class="px-6 py-4 bg-white border-b-2 border-gray-100" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <div class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-4">
                <!-- Month Selector -->
                <div class="flex items-center gap-3 w-full sm:w-auto justify-center">
                    <label class="text-sm font-bold text-gray-700">Month:</label>
                    <select id="monthSelector" 
                        class="px-4 py-2.5 text-sm font-semibold text-gray-800 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all cursor-pointer hover:border-brand-primary/50">
                        <option value="0">January</option>
                        <option value="1">February</option>
                        <option value="2">March</option>
                        <option value="3">April</option>
                        <option value="4">May</option>
                        <option value="5">June</option>
                        <option value="6">July</option>
                        <option value="7">August</option>
                        <option value="8">September</option>
                        <option value="9">October</option>
                        <option value="10">November</option>
                        <option value="11">December</option>
                    </select>
                </div>

                <!-- Year Selector -->
                <div class="flex items-center gap-3 w-full sm:w-auto justify-center">
                    <label class="text-sm font-bold text-gray-700">Year:</label>
                    <select id="yearSelector" 
                        class="px-4 py-2.5 text-sm font-semibold text-gray-800 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all cursor-pointer hover:border-brand-primary/50">
                        <!-- Years will be populated by JavaScript -->
                    </select>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex flex-wrap items-center justify-center gap-2 w-full sm:w-auto">
                    <button id="prevMonth" 
                        class="p-2.5 rounded-xl bg-gray-100 hover:bg-brand-primary hover:text-white text-gray-700 font-bold transition-all focus:outline-none focus:ring-2 focus:ring-brand-primary"
                        title="Previous Month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button id="nextMonth" 
                        class="p-2.5 rounded-xl bg-gray-100 hover:bg-brand-primary hover:text-white text-gray-700 font-bold transition-all focus:outline-none focus:ring-2 focus:ring-brand-primary"
                        title="Next Month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <button id="refreshButton" 
                        class="p-2.5 rounded-xl bg-emerald-100 hover:bg-emerald-500 hover:text-white text-emerald-700 font-bold transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer"
                        title="Refresh availability data">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden p-12 text-center" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <div class="flex flex-col items-center gap-4">
                <svg class="animate-spin h-12 w-12 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 font-medium">Loading availability data...</p>
            </div>
        </div>

        <!-- Table Container with Fixed Height & Internal Scroll -->
        <div id="tableContent" class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-320px)] sm:max-h-[calc(100vh-400px)]" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
            <table class="w-full min-w-max border-collapse">
                <thead class="sticky top-0 z-20 shadow-sm">
                    <tr class="bg-gradient-to-r from-brand-primary to-brand-secondary">
                        <th class="sticky left-0 z-30 bg-brand-primary px-6 py-5 text-left border-r border-white/10 shadow-md">
                            <div class="flex items-center gap-2.5 text-white font-bold text-base">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Day & Date</span>
                            </div>
                        </th>
                        <!-- Unit headers will be dynamically inserted here -->
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Table rows will be dynamically inserted here -->
                </tbody>
            </table>
        </div>

        <!-- Legend Section (Inside Card) -->
        <div class="p-5 bg-gradient-to-r from-gray-50 to-white border-t-2 border-gray-100" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
            <div class="flex flex-wrap justify-center items-center gap-4 md:gap-6">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 border-2 border-green-600 shadow-sm"></div>
                    <span class="font-semibold text-gray-800 text-sm">Available</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 border-2 border-yellow-500 shadow-sm"></div>
                    <span class="font-semibold text-gray-800 text-sm">Available (special price)</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-red-500 border-2 border-red-600 shadow-sm"></div>
                    <span class="font-semibold text-gray-800 text-sm">Not Available</span>
                </div>
            </div>
        </div>

        <!-- Last Update Info (Inside Card) -->
        <div class="px-4 py-3 bg-white text-center border-t border-gray-100" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
            <p class="text-xs text-gray-500 flex items-center justify-center gap-1 mb-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Last updated: <span id="lastUpdate" class="font-medium">{{ now()->format('d M Y H:i') }}</span>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Pass availability data from Laravel to JavaScript
    window.availabilityData = @json($availabilityData);
    window.areaUnitsData = @json($areaUnits);
    window.defaultArea = '{{ $defaultArea }}';
</script>
@vite('resources/js/pages/availability.js')
@endpush
@endsection
