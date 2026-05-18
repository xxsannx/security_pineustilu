<!-- Modal International -->
<div id="modalLuarNegeri" class="fixed inset-0 backdrop-blur-sm bg-black/20 z-50 items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[85vh] overflow-hidden shadow-2xl">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b-2 border-gray-100 p-6 md:p-8 flex justify-between items-start z-10">
            <div>
                <div class="flex items-center gap-4 mb-2">
                    <div class="bg-gradient-to-br from-[#017249] to-[#015a3a] p-3 rounded-2xl shadow-lg">
                        <!-- Globe Icon (International) -->
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3c-2 2.5-3 5.5-3 9s1 6.5 3 9c2-2.5 3-5.5 3-9s-1-6.5-3-9ZM4.5 7.5h15M4.5 16.5h15"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-800">International</h3>
                        <p class="text-gray-500 text-sm mt-1">Via International Airport</p>
                    </div>
                </div>
            </div>
            <button data-close-modal="luarNegeri" class="bg-gray-100 hover:bg-[#017249] hover:text-white p-2.5 rounded-full transition-all duration-300 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 md:p-8 space-y-6 overflow-y-auto max-h-[calc(85vh-120px)]">
            <!-- Pesawat + Kereta Regular -->
            <div class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-gray-100 hover:border-sky-300">
                <div class="flex items-start gap-5">
                    <div class="bg-gradient-to-br from-sky-500 to-sky-600 p-4 rounded-xl flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <!-- Airplane + Train Icon -->
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10l4-6h3l-1 4h5l1-4h3l-2 6M5 10v2h14v-2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 14h14v3a1 1 0 01-1 1H6a1 1 0 01-1-1v-3Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 18v2M16 18v2M9 14v3M15 14v3"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <h4 class="font-bold text-xl text-gray-800">Airplane + Train (Regular)</h4>
                            <span class="bg-sky-100 text-sky-700 text-xs font-semibold px-3 py-1 rounded-full">International</span>
                        </div>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Fly to Soekarno–Hatta International Airport (CGK, Jakarta) or Husein Sastranegara International Airport (BDO, Bandung). If you land in Jakarta, take a regular train from Gambir or Pasar Senen Station to Bandung. From Bandung, continue with local transportation, travel/minibus, or rent a car via Soreang Toll Road to Pangalengan (approximately 2 hours).
                        </p>
                        <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Flight + Transit</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Affordable</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesawat + Whoosh -->
            <div class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-gray-100 hover:border-orange-300">
                <div class="flex items-start gap-5">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-4 rounded-xl flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <!-- Airplane + High-Speed Train Icon -->
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 7l5 1 2-4h6l2 4h5M4 8l1 4h14l1-4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12v3a1 1 0 001 1h12a1 1 0 001-1v-3"/>
                            <circle cx="8" cy="14" r="1"/>
                            <circle cx="16" cy="14" r="1"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16v2M16 16v2M1 6l2 1M1 10l2-1"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <h4 class="font-bold text-xl text-gray-800">Airplane + High-Speed Train (Whoosh)</h4>
                            <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-3 py-1 rounded-full">Super Fast</span>
                        </div>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Fly to Soekarno–Hatta International Airport (CGK, Jakarta). From the airport, transfer to Halim Station (Jakarta) and take the Whoosh High-Speed Train to Padalarang Station (Bandung area). From Padalarang, continue to Bandung Station using the free feeder train provided by Whoosh. From Bandung Station, you can continue your journey with local public transportation, travel/minibus, or rent a car via Soreang Toll Road to reach Pangalengan (approximately 2 hours).
                        </p>
                        <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>High-Speed Train</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span>Modern & Comfortable</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesawat + Bus -->
            <div class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-gray-100 hover:border-teal-300">
                <div class="flex items-start gap-5">
                    <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-4 rounded-xl flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <!-- Airplane + Bus Icon -->
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 5l4 1 1-2h4l1 2h3"/>
                            <rect x="5" y="9" width="14" height="10" rx="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 14h14M8 9V7M16 9V7"/>
                            <circle cx="8" cy="17" r="1"/>
                            <circle cx="16" cy="17" r="1"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 19v1M16 19v1"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <h4 class="font-bold text-xl text-gray-800">Airplane + Bus / Travel</h4>
                            <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-3 py-1 rounded-full">Practical</span>
                        </div>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Fly to Soekarno–Hatta International Airport (CGK, Jakarta). From Jakarta, take an intercity bus or travel/minibus service to Bandung (Leuwipanjang Terminal or Cicaheum Terminal). After arriving in Bandung, continue with local public transportation, travel/minibus, or rent a car via Soreang Toll Road to reach Pangalengan (approximately 2 hours).
                        </p>
                        <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span>Flight + Bus</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Door to Door</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesawat + Mobil -->
            <div class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-gray-100 hover:border-indigo-300">
                <div class="flex items-start gap-5">
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-4 rounded-xl flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <!-- Airplane + Car Icon -->
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1 1-3h5l1 3h4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l2-4h10l2 4M4 12h16v5a1 1 0 01-1 1H5a1 1 0 01-1-1v-5Z"/>
                            <circle cx="7" cy="15" r="1.5"/>
                            <circle cx="17" cy="15" r="1.5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 18v2M17 18v2M10 15h4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <h4 class="font-bold text-xl text-gray-800">Airplane + Rental Car</h4>
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">Flexible</span>
                        </div>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Fly to Soekarno–Hatta International Airport (CGK, Jakarta) or Husein Sastranegara International Airport (BDO, Bandung). At both airports, you can rent a car directly. From Jakarta, drive via the Jakarta–Cikampek Toll Road and Cipularang Toll Road, exit at Soreang, then continue to Pangalengan (2 hours from Bandung).
                        </p>
                        <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Direct Car Rental</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>For Groups</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
