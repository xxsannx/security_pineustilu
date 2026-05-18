<section class="bg-gradient-to-br from-white to-[#f8fffe] rounded-2xl shadow-lg p-8 border-2 border-[#017249]/10 mt-6 hover:shadow-xl transition-shadow duration-300">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-[#017249]/10 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-[#017249] tracking-tight">Outbound Reservation Information</h2>
    </div>

    <form id="outReservasiForm" class="grid grid-cols-1 md:grid-cols-2 gap-8" autocomplete="off" novalidate>
        @csrf
        <!-- Hidden inputs for data -->
        <input type="hidden" name="outbound_id" id="selectedOutboundId">
        <input type="hidden" name="variant_id" id="selectedVariantId">
        <input type="hidden" name="checkin" id="hiddenCheckin">

        <div class="space-y-6">

            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/5">
                <h3 class="text-sm font-semibold text-[#017249] mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    Selected Activity
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1" for="outSelectedCat">Outbound Activity</label>
                        <input id="outSelectedCat" name="selectedOutbound" type="text" readonly
                            class="w-full rounded-2xl border-2 border-gray-200 px-3 py-2.5 bg-gray-50/80 text-gray-700 font-medium focus:border-[#017249]/30 transition-colors"
                            value="{{ $outbounds->first()->name ?? 'Arung Jeram' }}">
                    </div>
                    <div id="selectedVariantDisplay" class="hidden">
                        <label class="block text-xs text-gray-500 mb-1">Selected Variant</label>
                        <input id="outSelectedVariant" name="selectedVariant" type="text" readonly
                            class="w-full rounded-2xl border-2 border-gray-200 px-3 py-2.5 bg-gray-50/80 text-gray-700 font-medium focus:border-[#017249]/30 transition-colors"
                            value="-">
                    </div>
                </div>
                <!-- Price Display -->
                <div class="mt-3 p-3 bg-[#017249]/5 rounded-2xl">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Price:</span>
                        <span id="outUnitPrice" class="font-bold text-[#017249]">Rp {{ number_format($outbounds->first()->prices->first()->price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div id="outPriceType" class="text-xs text-gray-500 mt-1">
                        @if($outbounds->first())
                            {{ $outbounds->first()->pricing_type === 'per_unit' ? 'per ' . $outbounds->first()->unit_name : 'per person' }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/5">
                <label class="block text-sm font-semibold text-gray-700 mb-3" for="outGuestCount" id="guestLabel">Number of Participants</label>
                <div class="flex items-center gap-3">
                    <button type="button" id="outGuestDecrease" aria-label="Decrease participants"
                        class="w-10 h-10 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <input type="text" id="outGuestCount" name="guestCount" value="{{ $outbounds->first()->min_participants ?? 1 }}" readonly
                        class="guest-count-input w-16 text-center rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white text-gray-700 font-bold text-lg focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all"
                        aria-live="polite">
                    <button type="button" id="outGuestIncrease" aria-label="Increase participants"
                        class="w-10 h-10 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <span id="guestUnit" class="text-sm text-gray-600 ml-2">people</span>
                </div>
                <p id="outGuestInfo" class="text-xs text-gray-500 mt-2">
                    @if($outbounds->first())
                        Min: {{ $outbounds->first()->min_participants }} people
                        @if($outbounds->first()->max_participants)
                            | Max: {{ $outbounds->first()->max_participants }} people
                        @endif
                        @if($outbounds->first()->min_age)
                            | Min age: {{ $outbounds->first()->min_age }} years
                        @endif
                    @endif
                </p>
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
                        <label class="block text-xs text-gray-500 mb-1" for="outName">Full Name</label>
                        <input id="outName" name="name" type="text"
                            class="w-full rounded-2xl border-2 border-gray-200 px-4 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400"
                            placeholder="Enter full name" required>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1" for="outEmail">Email</label>
                            <input id="outEmail" name="email" type="email"
                                class="w-full rounded-2xl border-2 border-gray-200 px-4 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400"
                                placeholder="email@domain.com" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1" for="outPhone">Phone Number</label>
                            <input id="outPhone" name="phone" type="tel"
                                class="w-full rounded-2xl border-2 border-gray-200 px-4 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400"
                                placeholder="08xxxxxxxx" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="outAgree" name="agree" class="h-4 w-4" required>
                <label for="outAgree" class="text-sm text-gray-600">I Agree to the Applicable Terms &amp; Conditions</label>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#017249] to-[#0b5a3e] hover:from-[#0b5a3e] hover:to-[#017249] text-white rounded-xl py-4 font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2">
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
                    Packages & Add-ons
                </h3>

                <p class="text-sm text-gray-600 mb-4">Select additional packages to complete your outbound experience</p>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-2" id="extrasContainer" style="scrollbar-width: thin; scrollbar-color: #017249 #f1f5f9;">
                    <!-- Dokumentasi Foto/Video (conditional based on outbound) -->
                    <label id="dokumentasiOption" class="group flex items-center gap-4 p-3 rounded-2xl border-2 border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer {{ $outbounds->first() && $outbounds->first()->allows_documentation_addon ? '' : 'hidden' }}">
                        <div class="relative flex-shrink-0">
                            <input type="checkbox" name="extras[]" value="dokumentasi" data-price="100000"
                                class="extra-checkbox peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors">Photo/Video Documentation</div>
                            <div class="text-sm text-gray-500">Capture exciting moments of your outbound activity</div>
                            <div class="text-sm font-medium text-[#017249] mt-1">Rp 100,000 / boat</div>
                        </div>
                    </label>

                    <!-- Transportation (conditional based on outbound) -->
                    <label id="transportasiOption" class="group flex items-center gap-4 p-3 rounded-2xl border-2 border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer {{ $outbounds->first() && $outbounds->first()->requires_transportation ? '' : 'hidden' }}">
                        <div class="relative flex-shrink-0">
                            <input type="checkbox" name="extras[]" value="transportation" data-price="{{ $transportationPrice->price ?? 200000 }}"
                                class="extra-checkbox peer absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-md peer-checked:border-[#017249] peer-checked:bg-[#017249] transition-all duration-200 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800 group-hover:text-[#017249] transition-colors">Transportation (Pickup)</div>
                            <div class="text-sm text-gray-500">Pickup from glamping location to activity location</div>
                            <div class="text-sm font-medium text-[#017249] mt-1">Rp {{ number_format($transportationPrice->price ?? 200000, 0, ',', '.') }} / car</div>
                        </div>
                    </label>
                </div>

                <div class="mt-4 p-3 bg-blue-50 rounded-2xl border border-blue-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong>Info:</strong> All additional packages are optional and can be added according to your needs.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Summary -->
            <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-[#017249]/5">
                <h3 class="text-lg font-semibold text-[#017249] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Price Summary
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600" id="activityPriceLabel">Activity Price</span>
                        <span id="outActivityPriceDisplay">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600" id="quantityLabel">Quantity</span>
                        <span id="outQuantityDisplay">{{ $outbounds->first()->min_participants ?? 1 }} people</span>
                    </div>
                    <div class="flex justify-between text-sm" id="outExtrasRow" style="display: none;">
                        <span class="text-gray-600">Add-ons</span>
                        <span id="outExtrasDisplay">Rp 0</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between font-bold text-lg">
                        <span class="text-[#017249]">Total</span>
                        <span id="outTotalDisplay" class="text-[#017249]">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>