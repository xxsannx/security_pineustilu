<!-- Modal Info -->
<div id="infoModal" class="fixed inset-0 backdrop-blur-sm bg-black/20 z-[9999] items-center justify-center p-4 hidden"
    role="dialog" aria-modal="true" aria-labelledby="infoModalTitle">
    <div
        class="bg-gradient-to-br from-white to-gray-50 rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl border-2 border-gray-100">
        <!-- Modal Header -->
        <div
            class="sticky top-0 bg-white border-b-2 border-gray-100 p-6 md:p-8 flex justify-between items-center z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-br from-[#017249] to-[#015a3a] p-3 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
                <div>
                    <h3 id="infoModalTitle" class="text-2xl md:text-3xl font-bold text-gray-800"
                        style="font-family: 'Bizon', sans-serif;">Area Detail Information</h3>
                    <p class="text-gray-500 text-sm mt-1" id="infoModalSubtitle">Additional details of the selected area
                    </p>
                </div>
            </div>
            <button type="button" data-close-modal="info"
                class="bg-gray-100 hover:bg-[#017249] hover:text-white text-gray-600 p-3 rounded-full transition-all duration-300 hover:rotate-90 hover:scale-110 shadow-md cursor-pointer"
                aria-label="Close modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 md:p-8 space-y-6 overflow-y-auto max-h-[calc(90vh-180px)]">
            <!-- Loading State -->
            <div id="infoLoading" class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                <div class="animate-pulse space-y-4">
                    <div class="h-6 w-1/2 bg-gray-200 rounded"></div>
                    <div class="h-4 w-3/4 bg-gray-200 rounded"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="h-20 bg-gray-200 rounded-xl"></div>
                        <div class="h-20 bg-gray-200 rounded-xl"></div>
                    </div>
                    <div class="h-28 bg-gray-200 rounded-xl"></div>
                </div>
            </div>

            <!-- Error State -->
            <div id="infoError" class="hidden bg-white rounded-2xl p-6 shadow-lg border-2 border-red-100">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-bold text-gray-800">Failed to load information</div>
                        <div id="infoErrorText" class="text-sm text-gray-600 mt-1">Please try again.</div>
                    </div>
                </div>
            </div>

            <!-- Content State -->
            <div id="infoContent" class="hidden space-y-6">
                <!-- Facilities (Dropdown) -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                    <h4 class="font-bold text-xl text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#017249]"></span>
                        Facilities
                    </h4>

                    <div class="space-y-3">
                        <details class="group rounded-xl border-2 border-gray-100 bg-gray-50/40 overflow-hidden">
                            <summary
                                class="cursor-pointer list-none px-5 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-[#017249] to-[#015a3a] rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800">Private Facilities</div>
                                        <div class="text-xs text-gray-500" id="infoPrivateCount">0 item</div>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 group-open:rotate-180"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-5 pb-5">
                                <ul id="infoPrivateList"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700"></ul>
                            </div>
                        </details>

                        <details class="group rounded-xl border-2 border-gray-100 bg-gray-50/40 overflow-hidden">
                            <summary
                                class="cursor-pointer list-none px-5 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-[#017249] to-[#015a3a] rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800">Public Facilities</div>
                                        <div class="text-xs text-gray-500" id="infoPublicCount">0 item</div>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-300 group-open:rotate-180"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-5 pb-5">
                                <ul id="infoPublicList"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700"></ul>
                            </div>
                        </details>
                    </div>
                </div>

                <!-- Price + Capacity + Extra Charge (Non-dropdown) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Price -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                        <h4 class="font-bold text-xl text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#017249]"></span>
                            Price (Starting From)
                        </h4>
                        <div class="space-y-3">
                            <div
                                class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50/40 px-4 py-3">
                                <div class="text-sm text-gray-600 font-medium">Weekday</div>
                                <div id="infoPriceWeekday" class="text-sm font-bold text-[#017249]">-</div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50/40 px-4 py-3">
                                <div class="text-sm text-gray-600 font-medium">Weekend</div>
                                <div id="infoPriceWeekend" class="text-sm font-bold text-[#017249]">-</div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50/40 px-4 py-3">
                                <div class="text-sm text-gray-600 font-medium">High Season</div>
                                <div id="infoPriceHighSeason" class="text-sm font-bold text-[#017249]">-</div>
                            </div>
                            <p class="text-xs text-gray-500">Prices may vary depending on unit/deck and date.</p>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                        <h4 class="font-bold text-xl text-gray-800 mb-4 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#017249]"></span>
                            Capacity
                        </h4>
                        <div class="space-y-3">
                            <div
                                class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50/40 px-4 py-3">
                                <div class="text-sm text-gray-600 font-medium">Default</div>
                                <div id="infoCapacityDefault" class="text-sm font-bold text-gray-800">-</div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50/40 px-4 py-3">
                                <div class="text-sm text-gray-600 font-medium">Maximum</div>
                                <div id="infoCapacityMax" class="text-sm font-bold text-gray-800">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra Charge -->
                <div id="infoExtraChargeCard" class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                    <h4 class="font-bold text-xl text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#017249]"></span>
                        Extra Charge
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/40 px-4 py-4">
                            <div class="text-xs text-gray-500">Full Amenities</div>
                            <div id="infoExtraFull" class="text-sm font-bold text-[#017249] mt-1">-</div>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/40 px-4 py-4">
                            <div class="text-xs text-gray-500">Breakfast</div>
                            <div id="infoExtraBreakfast" class="text-sm font-bold text-[#017249] mt-1">-</div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Extra charge follows the selected area's policy.</p>
                </div>
            </div>
        </div>
    </div>
</div>
