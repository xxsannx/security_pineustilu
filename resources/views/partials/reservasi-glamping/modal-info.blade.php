<div id="infoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#017249] to-[#0b5a3e] px-6 py-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white" id="infoModalTitle">DETAILS — Pineus Tilu</h3>
                    <p class="text-white/80 text-sm" id="infoModalSubtitle">Facilities, prices, capacity, and extra charges</p>
                </div>
                <button id="closeModal" class="text-white/80 hover:text-white transition-colors text-2xl leading-none p-2">&times;</button>
            </div>
        </div>
        
        <!-- Content -->
        <div id="infoBody" class="p-6 overflow-y-auto max-h-[60vh]" style="scrollbar-width: thin;">
            <!-- Loading State -->
            <div id="infoLoading" class="hidden py-12 text-center">
                <div class="inline-block w-8 h-8 border-4 border-[#017249]/30 border-t-[#017249] rounded-full animate-spin mb-4"></div>
                <p class="text-gray-500">Loading area information...</p>
            </div>

            <!-- Error State -->
            <div id="infoError" class="hidden py-12 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <p id="infoErrorText" class="text-gray-600 mb-4">Failed to load information</p>
                <button id="infoRetryBtn" class="px-4 py-2 bg-[#017249] text-white rounded-xl text-sm hover:bg-[#015a3a] transition">
                    Try Again
                </button>
            </div>

            <!-- Content State -->
            <div id="infoContent" class="hidden">
                <!-- Facilities Section with Collapsible -->
                <div class="mb-6">
                    <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-[#017249] rounded-full"></span>
                        Facilities
                    </h4>
                    
                    <!-- Private Facilities (Collapsible) -->
                    <div class="mb-3">
                        <button type="button" id="togglePrivateFacilities" class="w-full flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200 hover:border-[#017249]/30 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-semibold text-gray-800">Private Facilities</div>
                                    <div id="infoPrivateCount" class="text-sm text-gray-500">0 item</div>
                                </div>
                            </div>
                            <svg id="privateChevron" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="privateFacilitiesPanel" class="hidden mt-2 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div id="infoPrivateList" class="grid grid-cols-2 gap-2">
                                <span class="text-sm text-gray-500">No facilities data available.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Public Facilities (Collapsible) -->
                    <div>
                        <button type="button" id="togglePublicFacilities" class="w-full flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200 hover:border-[#017249]/30 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#017249] rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-semibold text-gray-800">Public Facilities</div>
                                    <div id="infoPublicCount" class="text-sm text-gray-500">0 item</div>
                                </div>
                            </div>
                            <svg id="publicChevron" class="w-5 h-5 text-gray-400 transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="publicFacilitiesPanel" class="mt-2 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div id="infoPublicList" class="grid grid-cols-2 gap-2">
                                <span class="text-sm text-gray-500">No facilities data available.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price & Capacity Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                    <!-- Price (Starting From) -->
                    <div class="bg-white rounded-2xl p-4 border border-gray-200">
                        <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 bg-[#017249] rounded-full"></span>
                            Price (Starting From)
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Weekday</span>
                                <span id="infoPriceWeekday" class="font-bold text-[#017249]">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Weekend</span>
                                <span id="infoPriceWeekend" class="font-bold text-[#017249]">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">High Season</span>
                                <span id="infoPriceHighSeason" class="font-bold text-[#017249]">-</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3">Prices may vary depending on unit/deck and date.</p>
                    </div>

                    <!-- Capacity -->
                    <div class="bg-white rounded-2xl p-4 border border-gray-200">
                        <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 bg-[#017249] rounded-full"></span>
                            Capacity
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Default</span>
                                <span id="infoCapacityDefault" class="font-bold text-gray-800">- people</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Maximum</span>
                                <span id="infoCapacityMax" class="font-bold text-gray-800">- people</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra Charge Section -->
                <div class="mb-6">
                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-[#017249] rounded-full"></span>
                        Extra Guest Charges
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-green-50 to-green-100/50 rounded-2xl p-4 border border-green-200">
                            <div class="text-sm text-gray-600 mb-1">Full (Amenities + Breakfast)</div>
                            <div id="infoExtraFull" class="text-lg font-bold text-[#017249]">-</div>
                            <div class="text-xs text-gray-500">per extra guest</div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-4 border border-blue-200">
                            <div class="text-sm text-gray-600 mb-1">Breakfast Only</div>
                            <div id="infoExtraBreakfast" class="text-lg font-bold text-blue-600">-</div>
                            <div class="text-xs text-gray-500">per extra guest</div>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-amber-800 text-sm">Important</div>
                            <p class="text-sm text-amber-700">Check-in time is 14:00 WIB and check-out is 12:00 WIB. Extra charges apply for guests exceeding the default capacity.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <span id="infoFooterText" class="text-xs text-gray-500">Showing area details</span>
            <button id="closeModal2" type="button" class="px-6 py-2.5 bg-gradient-to-r from-[#017249] to-[#0b5a3e] text-white rounded-2xl font-medium hover:shadow-lg transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Toggle private facilities collapsible
document.getElementById('togglePrivateFacilities')?.addEventListener('click', function() {
    const panel = document.getElementById('privateFacilitiesPanel');
    const chevron = document.getElementById('privateChevron');
    if (panel && chevron) {
        panel.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
});

// Toggle public facilities collapsible
document.getElementById('togglePublicFacilities')?.addEventListener('click', function() {
    const panel = document.getElementById('publicFacilitiesPanel');
    const chevron = document.getElementById('publicChevron');
    if (panel && chevron) {
        panel.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
});
</script>
