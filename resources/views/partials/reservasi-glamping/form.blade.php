{{--
    DEPRECATED (legacy partial)

    Active glamping reservation page using:
    - resources/views/reservasi/reservasi-glamping.blade.php (main form: #reservasiForm)
    - resources/views/partials/reservasi-glamping/detail.blade.php
    - resources/views/partials/reservasi-glamping/preview-detail.blade.php
    - resources/views/partials/modals/modal-amenities.blade.php (amenities qty +/-)

    This partial is intentionally disabled to prevent:
    - nested <form> + duplicate id="reservasiForm" (invalid HTML)
    - old amenities format (checkbox)
--}}
@php
    return;
@endphp

<section class="bg-gradient-to-br from-white to-[#f8fffe] rounded-2xl shadow-lg p-8 border-2 border-[#017249]/10 mt-6 hover:shadow-xl transition-shadow duration-300">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-[#017249] tracking-tight">Glamping Reservation Information</h2>
    </div>

    <form method="POST" action="{{ route('reservasi.glamping.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-8" id="reservasiForm" autocomplete="off" novalidate onsubmit="return handleFormSubmit(event)">
        @csrf
        <div class="space-y-6">

            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/5">
                <label class="block text-sm font-semibold text-gray-700 mb-3" for="guestCount">Number of Visitors</label>
                <div class="flex items-center gap-3">
                    <button type="button" id="guestDecrease" aria-label="Decrease visitors"
                        class="w-10 h-10 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <input type="text" id="guestCount" name="guestCount" value="1" readonly
                        class="guest-count-input w-16 text-center rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white text-gray-700 font-bold text-lg focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all"
                        aria-live="polite">
                    <button type="button" id="guestIncrease" aria-label="Add visitor"
                        class="w-10 h-10 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <span class="text-sm text-gray-600 ml-2">people</span>
                </div>
            </div>

            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Contact Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1" for="name">Full Name</label>
                        <input id="name" name="name" type="text"
                            class="w-full rounded-2xl border-2 border-gray-200 px-4 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400"
                            placeholder="Enter full name">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1" for="phone">Phone Number</label>
                            <input id="phone" name="phone" type="tel"
                                class="w-full rounded-2xl border-2 border-gray-200 px-4 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400"
                                placeholder="08xxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1" for="email">Email</label>
                            <input id="email" name="email" type="email"
                                class="w-full rounded-2xl border-2 border-gray-200 px-4 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400"
                                placeholder="email@domain.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="agree" name="agree" class="h-4 w-4">
                <label for="agree" class="text-sm text-gray-600">I Agree to the Terms &amp; Conditions</label>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#017249] to-[#0b5a3e] hover:from-[#0b5a3e] hover:to-[#017249] text-white rounded-xl py-4 font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Book Now
                </button>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-[#017249]/5">
                <h3 class="text-lg font-semibold text-[#017249] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Extra Amenities
                </h3>

                <p class="text-sm text-gray-600 mb-4">Choose extra amenities to enhance your stay experience</p>

                <div class="space-y-3">
                    
                    <!-- Accordion: Equipment & Services -->
                    <div class="border-2 border-gray-100 rounded-2xl overflow-hidden">
                        <button type="button"
                            class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-[#017249]/5 to-transparent hover:from-[#017249]/10 transition-all duration-200"
                            onclick="toggleAccordion('perlengkapan')">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-800">Equipment & Services</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" id="icon-perlengkapan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="accordion-perlengkapan" class="hidden">
                            <div class="p-4 space-y-3 bg-white">
                                <!-- Pax Amenities 1,2,4 -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="pax_124"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Amenities (Mattress, Sleeping bag, Sarapen)</div>
                                        <div class="text-xs text-gray-500">Package for 1-4 people</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 100.000 / Pax</div>
                                    </div>
                                </label>

                                <!-- Pax Amenities VIP -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="pax_vip3"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Amenities VIP (3 VIP)</div>
                                        <div class="text-xs text-gray-500">Kasur, kantong tidur, sarapen & air mandi</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 150.000 / Pax</div>
                                    </div>
                                </label>

                                <!-- Extra Breakfast -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="sarapan_tambahan"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Extra Breakfast</div>
                                        <div class="text-xs text-gray-500">Buffet - all you can eat</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 40.000 / Pax</div>
                                    </div>
                                </label>

                                <!-- Toiletry Set -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="set_alat_mandi"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Toiletry Set</div>
                                        <div class="text-xs text-gray-500">1 towel, 1 toothbrush, toothpaste</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 25.000 / Set</div>
                                    </div>
                                </label>

                                <!-- Portable Stove Set -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="set_kompor_portabel"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Portable Stove Set</div>
                                        <div class="text-xs text-gray-500">1 stove, 1 gas, 1 pan, 1 pot, 1 spatula</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 100.000 / Set</div>
                                    </div>
                                </label>

                                <!-- Dining Set -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="alat_makan"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Dining Set</div>
                                        <div class="text-xs text-gray-500">Plate, spoon, fork, chopsticks, glass, bowl</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 15.000 / Set</div>
                                    </div>
                                </label>

                                <!-- Arang -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="arang"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Charcoal</div>
                                        <div class="text-xs text-gray-500">For BBQ or campfire</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 50.000 / Bag</div>
                                    </div>
                                </label>

                                <!-- Firewood -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="kayu_bakar"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Firewood</div>
                                        <div class="text-xs text-gray-500">For campfire or cooking</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 50.000 / Bundle</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion: Meat & Sauce -->
                    <div class="border-2 border-gray-100 rounded-2xl overflow-hidden">
                        <button type="button"
                            class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-red-50/50 to-transparent hover:from-red-50 transition-all duration-200"
                            onclick="toggleAccordion('daging')">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-800">Meat & Sauce</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" id="icon-daging" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="accordion-daging" class="hidden">
                            <div class="p-4 space-y-3 bg-white">
                                <!-- Beef Sirloin -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="beef_sirloin"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Beef Sirloin</div>
                                        <div class="text-xs text-gray-500">4 pcs / 500g premium beef</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 98.000</div>
                                    </div>
                                </label>

                                <!-- Beef Slice Short Plate -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="beef_slice_short"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Beef Slice Short Plate</div>
                                        <div class="text-xs text-gray-500">500g sliced beef</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 79.000</div>
                                    </div>
                                </label>

                                <!-- Beef Slice Low Fat -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="beef_slice_lowfat"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Beef Slice Low Fat</div>
                                        <div class="text-xs text-gray-500">500g low fat beef</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 85.000</div>
                                    </div>
                                </label>

                                <!-- Cocktail Sausage Original -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="sosis_cocktail"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Cocktail Sausage Original</div>
                                        <div class="text-xs text-gray-500">Small size 500g</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 29.000</div>
                                    </div>
                                </label>

                                <!-- Beef Frank Sausage Original -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="sosis_beef_frank"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Beef Frank Sausage Original</div>
                                        <div class="text-xs text-gray-500">Medium size 500g</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 29.000</div>
                                    </div>
                                </label>

                                <!-- Super Meatball -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="super_meatball"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Super Meatball</div>
                                        <div class="text-xs text-gray-500">35-38 pcs / 500g</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 35.000</div>
                                    </div>
                                </label>

                                <!-- BBQ Sauce -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="bbq_sauce"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">BBQ Sauce</div>
                                        <div class="text-xs text-gray-500">300ml botol</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 39.000</div>
                                    </div>
                                </label>

                                <!-- Bulgogi Sauce -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="bulgogi_sauce"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Bulgogi Sauce</div>
                                        <div class="text-xs text-gray-500">300ml botol</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 39.000</div>
                                    </div>
                                </label>

                                <!-- Gochujang Sauce -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="gochujang_sauce"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Gochujang Sauce</div>
                                        <div class="text-xs text-gray-500">300ml Korean chili paste</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 39.000</div>
                                    </div>
                                </label>

                                <!-- Blackpepper Teriyaki Sauce -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="blackpepper_teriyaki_sauce"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Blackpepper Teriyaki Sauce</div>
                                        <div class="text-xs text-gray-500">300ml botol</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 29.000</div>
                                    </div>
                                </label>

                                <!-- Garlic Teriyaki Sauce -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="garlic_teriyaki_sauce"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Garlic Teriyaki Sauce</div>
                                        <div class="text-xs text-gray-500">300ml botol</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 29.000</div>
                                    </div>
                                </label>

                                <!-- Korean BBQ Sauce -->
                                <label class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox" name="amenities[]" value="korean_bbq_sauce"
                                            class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors truncate">Korean BBQ Sauce</div>
                                        <div class="text-xs text-gray-500">500ml premium sauce</div>
                                        <div class="text-sm font-medium text-[#017249] mt-1">Rp 55.000</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-blue-50 rounded-2xl border border-blue-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong>Info:</strong> All amenities are optional and can be added according to your needs.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>