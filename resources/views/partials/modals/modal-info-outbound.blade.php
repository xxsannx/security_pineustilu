<div id="outInfoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#017249] to-[#0b5a3e] px-6 py-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white" id="outInfoTitle">Outbound Information</h3>
                    <p class="text-white/80 text-sm mt-1" id="outInfoSubtitle">Outbound activity details</p>
                </div>
                <button id="outCloseModal" class="text-white/80 hover:text-white transition-colors text-2xl leading-none">&times;</button>
            </div>
        </div>
        
        <!-- Content -->
        <div id="outInfoBody" class="p-6 overflow-y-auto max-h-[60vh]" style="scrollbar-width: thin;">
            <!-- Description Section -->
            <div id="outDescriptionSection" class="mb-6">
                <h4 class="text-sm font-semibold text-[#017249] mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Description
                </h4>
                <p id="outDescriptionContent" class="text-sm text-gray-600 leading-relaxed">
                    Activity description will be displayed here.
                </p>
            </div>

            <!-- Activity Info Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-2xl p-3 text-center">
                    <div class="w-8 h-8 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Duration</div>
                    <div id="outDurationInfo" class="font-semibold text-gray-800 text-sm">-</div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-3 text-center">
                    <div class="w-8 h-8 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Distance</div>
                    <div id="outDistanceInfo" class="font-semibold text-gray-800 text-sm">-</div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-3 text-center">
                    <div class="w-8 h-8 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Participants</div>
                    <div id="outParticipantsInfo" class="font-semibold text-gray-800 text-sm">-</div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-3 text-center">
                    <div class="w-8 h-8 bg-[#017249]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-500">Min Age</div>
                    <div id="outAgeInfo" class="font-semibold text-gray-800 text-sm">-</div>
                </div>
            </div>

            <!-- Facilities Section -->
            <div id="outFacilitiesSection" class="mb-6">
                <h4 class="text-sm font-semibold text-[#017249] mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Facilities Included
                </h4>
                <div id="outFacilitiesList" class="grid grid-cols-2 gap-2">
                    <!-- Facilities will be populated dynamically -->
                </div>
            </div>

            <!-- Pricing Section -->
            <div id="outPricingSection" class="mb-6">
                <h4 class="text-sm font-semibold text-[#017249] mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pricing Information
                </h4>
                
                <!-- Base Price (for activities without variants) -->
                <div id="outBasePriceSection" class="bg-[#017249]/5 rounded-2xl p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Base Price</span>
                        <span id="outBasePrice" class="font-bold text-[#017249] text-lg">Rp 0</span>
                    </div>
                    <div id="outPriceUnit" class="text-xs text-gray-500 mt-1">per person</div>
                </div>

                <!-- Variants Pricing (for activities with variants) -->
                <div id="outVariantsPricing" class="hidden space-y-2">
                    <p class="text-sm text-gray-600 mb-2">This activity has several variants with different prices:</p>
                    <div id="outVariantsList" class="space-y-2">
                        <!-- Variants will be populated dynamically -->
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div id="outAdditionalSection" class="space-y-3">
                <!-- Transportation Notice -->
                <div id="outTransportNotice" class="hidden bg-blue-50 rounded-2xl p-3 border border-blue-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong>Transportation:</strong> This activity requires transportation from the glamping location to the activity site. Transportation fee <span id="outTransportPrice" class="font-semibold">Rp 200,000</span>/car (optional).
                        </div>
                    </div>
                </div>

                <!-- Documentation Addon Notice -->
                <div id="outDocuNotice" class="hidden bg-purple-50 rounded-2xl p-3 border border-purple-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                        <div class="text-sm text-purple-800">
                            <strong>Documentation:</strong> Photo/video documentation package available with additional fee of <span class="font-semibold">Rp 100,000</span>/boat.
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-amber-50 rounded-2xl p-3 border border-amber-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="text-sm text-amber-800">
                            <strong>Important:</strong> Participants must be in good health and not have excessive fear. Price includes safety equipment and experienced guides.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <button id="outCloseModal2" class="px-6 py-2.5 bg-gradient-to-r from-[#017249] to-[#0b5a3e] text-white rounded-2xl font-medium hover:shadow-lg transition-all">
                Close
            </button>
        </div>
    </div>
</div>