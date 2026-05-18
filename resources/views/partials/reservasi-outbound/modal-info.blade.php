<div id="outInfoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
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
                    <h3 class="text-xl font-bold text-white" id="outInfoTitle">DETAILS — Outbound Activity</h3>
                    <p class="text-white/80 text-sm" id="outInfoSubtitle">Facilities, prices, and activity details</p>
                </div>
                <button id="outCloseModal" class="text-white/80 hover:text-white transition-colors text-2xl leading-none p-2">&times;</button>
            </div>
        </div>
        
        <!-- Content -->
        <div id="outInfoBody" class="p-6 overflow-y-auto max-h-[60vh]" style="scrollbar-width: thin;">
            <!-- Facilities Section with Collapsible -->
            <div class="mb-6">
                <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-[#017249] rounded-full"></span>
                    Facilities
                </h4>
                
                <!-- Private Facilities (Collapsible) -->
                <div class="mb-3">
                    <button type="button" id="outToggleFacilities" class="w-full flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200 hover:border-[#017249]/30 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="font-semibold text-gray-800">Private Facilities</div>
                                <div id="outFacilitiesCount" class="text-sm text-gray-500">0 item</div>
                            </div>
                        </div>
                        <svg id="outFacilitiesChevron" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="outFacilitiesPanel" class="hidden mt-2 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div id="outFacilitiesList" class="grid grid-cols-2 gap-2">
                            <span class="text-sm text-gray-500">No facilities data available.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Info Grid -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Duration -->
                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                    <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Duration</div>
                    <div id="outDurationInfo" class="font-semibold text-gray-800">-</div>
                </div>
                <!-- Distance -->
                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                    <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Distance</div>
                    <div id="outDistanceInfo" class="font-semibold text-gray-800">-</div>
                </div>
                <!-- Participants -->
                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                    <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Participants</div>
                    <div id="outParticipantsInfo" class="font-semibold text-gray-800">-</div>
                </div>
                <!-- Min Age -->
                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                    <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Min Age</div>
                    <div id="outAgeInfo" class="font-semibold text-gray-800">-</div>
                </div>
            </div>

            <!-- Price Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <!-- Price (Starting From) -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200">
                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-[#017249] rounded-full"></span>
                        Price (Starting From)
                    </h4>
                    
                    <!-- Base Price (for activities without variants) -->
                    <div id="outBasePriceSection" class="space-y-2">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Base Price</span>
                            <span id="outBasePrice" class="font-bold text-[#017249]">Rp 0</span>
                        </div>
                        <div id="outPriceUnit" class="text-xs text-gray-500">per person</div>
                    </div>

                    <!-- Variants Pricing (for activities with variants) -->
                    <div id="outVariantsPricing" class="hidden space-y-2">
                        <div id="outVariantsList" class="space-y-2">
                            <!-- Variants will be populated dynamically -->
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-400 mt-3">Prices may vary depending on variant selection.</p>
                </div>

                <!-- Capacity -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200">
                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-[#017249] rounded-full"></span>
                        Capacity
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Min Participants</span>
                            <span id="outMinParticipants" class="font-bold text-gray-800">1 person</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Max Participants</span>
                            <span id="outMaxParticipants" class="font-bold text-gray-800">∞</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="space-y-3">
                <!-- Transportation Notice -->
                <div id="outTransportNotice" class="hidden bg-blue-50 rounded-2xl p-4 border border-blue-200">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-blue-800 text-sm">Transportation</div>
                            <p class="text-sm text-blue-700">This activity requires transportation. Fee: <span id="outTransportPrice" class="font-bold">Rp 200,000</span>/car (optional).</p>
                        </div>
                    </div>
                </div>

                <!-- Documentation Addon Notice -->
                <div id="outDocuNotice" class="hidden bg-purple-50 rounded-2xl p-4 border border-purple-200">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-purple-800 text-sm">Documentation</div>
                            <p class="text-sm text-purple-700">Photo/video documentation package available: <span class="font-bold">Rp 100,000</span>/boat.</p>
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
                            <p class="text-sm text-amber-700">Participants must be in good health. Price includes safety equipment and experienced guides.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <button id="outCloseModal2" type="button" class="px-6 py-2.5 bg-gradient-to-r from-[#017249] to-[#0b5a3e] text-white rounded-2xl font-medium hover:shadow-lg transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Toggle facilities collapsible
document.getElementById('outToggleFacilities')?.addEventListener('click', function() {
    const panel = document.getElementById('outFacilitiesPanel');
    const chevron = document.getElementById('outFacilitiesChevron');
    if (panel && chevron) {
        panel.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
});
</script>
