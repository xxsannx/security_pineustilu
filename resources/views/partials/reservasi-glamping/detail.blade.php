<section class="bg-white rounded-xl shadow-md p-6 border-2 border-[#0b5a3e]/10">
    <h2 class="text-2xl font-semibold text-[#017249] mb-6" style="font-family: 'Bizon', sans-serif;">Reservation Details</h2>

    @php
        $prefill = $contactPrefill ?? [];
        $isAuth = (bool) ($prefill['is_authenticated'] ?? false);
        $isGoogle = (bool) ($prefill['is_google'] ?? false);
        $countryCodeDefault = old('country_code', $prefill['country_code'] ?? '+62');
        $phoneDisplayPrefill = (string) ($prefill['phone'] ?? '');
        $phoneDigits = preg_replace('/\D+/', '', $phoneDisplayPrefill);
        $codeDigits = preg_replace('/\D+/', '', (string) $countryCodeDefault);
        if ($phoneDisplayPrefill !== '' && $codeDigits === '62') {
            if (str_starts_with($phoneDisplayPrefill, '+') && str_starts_with($phoneDigits, $codeDigits)) {
                $local = substr($phoneDigits, strlen($codeDigits));
                if ($local !== '') $phoneDisplayPrefill = '0' . ltrim($local, '0');
            } elseif (str_starts_with($phoneDigits, $codeDigits)) {
                $local = substr($phoneDigits, strlen($codeDigits));
                if ($local !== '') $phoneDisplayPrefill = '0' . ltrim($local, '0');
            } elseif (!str_starts_with($phoneDigits, '0') && str_starts_with($phoneDigits, '8')) {
                $phoneDisplayPrefill = '0' . $phoneDigits;
            }
        }

        $itemsCollection = collect($items ?? []);
        $amenitiesDesc = (string) ($itemsCollection->firstWhere('name', 'Amenities')['description'] ?? 'Foam mattress, sleeping bag, breakfast');
        $amenitiesVipDesc = (string) ($itemsCollection->firstWhere('name', 'Amenities VIP')['description'] ?? $amenitiesDesc);
        $extraBreakfastDesc = (string) ($itemsCollection->firstWhere('name', 'Extra Breakfast')['description'] ?? 'Breakfast only');
    @endphp

    <!-- Top: Check-in, Check-out, Selected Area, Selected Unit -->
    <div class="space-y-5 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-xs text-gray-600 font-medium" for="checkin">Check-in Date</label>
                <div class="relative">
                    <input id="checkin" name="checkin" type="text" readonly
                        class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 pr-10 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 text-gray-700 font-medium cursor-pointer text-sm"
                        placeholder="{{ now()->format('d/m/Y') }}"
                        value="{{ old('checkin', $checkinDefault ?? '') }}" />
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-gray-500">Starts at 14:00 WIB</p>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs text-gray-600 font-medium" for="checkout_display">Check-out Date</label>
                <div class="relative">
                    <input id="checkout_display" type="text" readonly
                        class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 pr-10 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 text-gray-700 font-medium cursor-pointer text-sm"
                        placeholder="{{ now()->addDay()->format('d/m/Y') }}" />
                    <input id="checkout" name="checkout" type="hidden" />
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-gray-500">Before 12:00 WIB</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-xs text-gray-600 font-medium" for="selectedArea">Selected Area</label>
                <input id="selectedArea" name="selectedArea" type="text" readonly
                    class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-gray-50/80 text-gray-700 font-medium text-sm"
                    value="Pineus Tilu 1">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs text-gray-600 font-medium" for="selectedUnit">Selected Unit</label>
                <input id="selectedUnit" name="selectedUnit" type="text" readonly
                    class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-gray-50/80 text-gray-700 font-medium text-sm"
                    value="-">
            </div>
        </div>

        <p id="bookingFlowMessage" class="hidden text-xs text-red-600 font-medium" aria-live="polite"></p>
    </div>

    <!-- Area Selector -->
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Choose Area</h3>
        <div id="areaSelector">
            @php
                $areaBtnClass =
                    'justify-self-center border-0 px-2 sm:px-3 py-2 text-[#017249] font-extrabold cursor-pointer text-center rounded-xl transition duration-200 min-w-0 sm:min-w-[100px] w-full bg-[#e7f4ef] hover:bg-[#d9efe7] focus:outline-none focus:ring-2 focus:ring-[#017249]/40 aria-pressed:bg-[#017249] aria-pressed:text-white aria-pressed:shadow-[0_2px_8px_rgba(1,50,30,0.12)] flex flex-col items-center justify-center h-16 sm:h-[68px]';
                $areaBtnCabinClass =
                    'justify-self-center border-0 px-2 sm:px-3 py-2 text-white font-extrabold cursor-pointer text-center rounded-xl transition duration-200 min-w-0 sm:min-w-[100px] w-full bg-[#B98C4F] hover:bg-[#A67E45] focus:outline-none focus:ring-2 focus:ring-[#B98C4F]/50 aria-pressed:bg-[#9C773E] aria-pressed:text-white aria-pressed:shadow-[0_2px_8px_rgba(185,140,79,0.35)] flex flex-col items-center justify-center h-16 sm:h-[68px]';
            @endphp
            <div class="h-[6px] bg-[#e6f6ef] rounded-full relative" id="areaTrack" aria-hidden="true">
                <div id="areaKnob"
                    class="w-5 h-5 rounded-full bg-[#017249] absolute top-1/2 -translate-y-1/2 transition-[left] duration-[320ms] shadow-[0_2px_6px_rgba(1,50,30,0.18)]"
                    style="left:0;"></div>
            </div>

            <div id="areaItems" class="grid grid-cols-3 sm:grid-cols-6 gap-1.5 sm:gap-2 mt-4 select-none w-full items-start"
                aria-label="Select Area">
                <button type="button" class="{{ $areaBtnClass }}" aria-pressed="false" data-area="pineus-tilu-1">
                    <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                    <div class="text-sm sm:text-sm ">1</div>
                </button>
                <button type="button" class="{{ $areaBtnClass }}" aria-pressed="false" data-area="pineus-tilu-2">
                    <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                    <div class="text-sm sm:text-sm ">2</div>
                </button>
                <button type="button" class="{{ $areaBtnClass }}" aria-pressed="false" data-area="pineus-tilu-3-vip">
                    <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                    <div class="text-sm sm:text-sm ">3 <span class="text-sm sm:text-sm ">VIP</span></div>
                </button>
                <button type="button" class="{{ $areaBtnClass }}" aria-pressed="false" data-area="pineus-tilu-4">
                    <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                    <div class="text-sm sm:text-sm ">4</div>
                </button>
                <button type="button" class="{{ $areaBtnCabinClass }}" aria-pressed="false" data-area="pineus-tilu-cabin">
                    <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                    <span class="text-sm sm:text-sm ">CABIN VIP</span>
                </button>
                <button type="button" class="{{ $areaBtnCabinClass }}" aria-pressed="false" data-area="pineus-tilu-cabin-vvip">
                    <div class="text-sm sm:text-sm leading-tight">PINEUS TILU</div>
                    <div class="text-sm sm:text-sm ">CABIN <span class="text-sm sm:text-sm ">VVIP</span></div>
                </button>
            </div>
        </div>
    </div>

    <!-- Map (below top fields) -->
    <div class="mb-6">
        <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200 overflow-hidden">
            <div class="w-full aspect-[4/3] flex items-center justify-center">
                <x-glamping-map :availability="$availability" :area-units="$areaUnits ?? []" :items="$items ?? []" :unit-prices="$unitPrices ?? []"
                    :unit-extra-charges="$unitExtraCharges ?? []" :high-season-ranges="$highSeasonRanges ?? []" class="w-full h-full object-contain" />
            </div>
            <div class="flex items-center justify-end gap-4 mt-4 text-xs text-gray-500">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 inline-block rounded-full bg-green-500"></span> Available
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 inline-block rounded-full bg-red-400"></span> Not available
                </span>
            </div>
        </div>

        <div class="mt-3">
            <button id="infoBtn" type="button"
                class="flex items-center gap-2 text-sm text-[#017249] underline decoration-dotted cursor-pointer hover:text-[#0b5a3e] transition-colors">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-green-200 text-[#017249] text-xs">i</span>
                Click to view area details
            </button>
        </div>
    </div>

    <!-- User Form: hide contact fields when rescheduling (use original booking credentials) -->
    @unless($hideContactFields ?? false)
    <div class="space-y-3 pt-4 border-t border-gray-200 mb-6">
        <div>
            <label class="block text-xs text-gray-600 font-medium mb-1.5" for="name">Full Name</label>
            <input id="name" name="name" type="text"
                class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400 text-sm"
                placeholder="John Doe" value="{{ old('name', $isAuth ? $prefill['name'] ?? '' : '') }}"
                {{ $isAuth ? 'readonly' : '' }}>
        </div>

        <div>
            <label class="block text-xs text-gray-600 font-medium mb-1.5" for="email">Email</label>
            <input id="email" name="email" type="email"
                class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400 text-sm"
                placeholder="john@email.com" value="{{ old('email', $isAuth ? $prefill['email'] ?? '' : '') }}"
                {{ $isAuth ? 'readonly' : '' }}>
        </div>

        <div>
            <label class="block text-xs text-gray-600 font-medium mb-1.5" for="phone">Phone Number</label>
            <div class="flex gap-2">
                <select id="country_code" name="country_code"
                    class="w-28 rounded-xl border-2 border-gray-200 px-2 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 text-sm">
                    <option value="+62" {{ $countryCodeDefault === '+62' ? 'selected' : '' }}>+62</option>
                    <option value="+60" {{ $countryCodeDefault === '+60' ? 'selected' : '' }}>+60</option>
                    <option value="+65" {{ $countryCodeDefault === '+65' ? 'selected' : '' }}>+65</option>
                    <option value="+1" {{ $countryCodeDefault === '+1' ? 'selected' : '' }}>+1</option>
                    <option value="+44" {{ $countryCodeDefault === '+44' ? 'selected' : '' }}>+44</option>
                    <option value="+81" {{ $countryCodeDefault === '+81' ? 'selected' : '' }}>+81</option>
                </select>
                <input id="phone" name="phone" type="tel"
                    class="flex-1 rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400 text-sm"
                    placeholder="08123456789" value="{{ old('phone', $phoneDisplayPrefill) }}"
                    {{ $isAuth && !$isGoogle ? 'readonly' : '' }}>
            </div>
        </div>
    </div>
    @endunless

    <!-- Number of Guests -->
    <div class="space-y-1.5 mb-6">
        <label class="block text-xs text-gray-600 font-medium" for="guestCount">Number of Guests</label>
        <div class="flex items-center gap-3">
            <button type="button" id="guestDecrease" aria-label="Decrease guests"
                class="w-9 h-9 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold shadow-sm hover:shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
            <input type="text" id="guestCount" name="guestCount" value="1" readonly
                class="guest-count-input w-14 text-center rounded-xl border-2 border-gray-200 px-2 py-2 bg-white text-gray-700 font-bold text-base focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all"
                aria-live="polite" disabled>
            <button type="button" id="guestIncrease" aria-label="Increase guests"
                class="w-9 h-9 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold shadow-sm hover:shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            <span class="text-xs text-gray-600">people</span>
        </div>
    </div>

    <!-- Extra Guest Charge Mode -->
    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/10 mb-6" id="extraGuestModeCard"
        data-amenities-desc="{{ e($amenitiesDesc) }}"
        data-amenities-vip-desc="{{ e($amenitiesVipDesc) }}"
        data-breakfast-desc="{{ e($extraBreakfastDesc) }}">
        <div class="flex items-center justify-between gap-3 mb-2">
            <h3 class="text-sm font-semibold text-gray-700">Extra Guest Option</h3>
            <span id="extraGuestInfo" class="text-xs text-gray-500">No extra guest</span>
        </div>
        <p class="text-xs text-gray-600 mb-3">Choose extra guest service: Full Amenities or Extra Breakfast.</p>
        <input type="hidden" id="extraChargeMode" name="extra_charge_mode" value="">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <button type="button" id="extraModeFull"
                class="rounded-xl border border-gray-200 bg-white text-left px-3 py-2 transition-all duration-200 cursor-pointer hover:border-[#017249]/40 flex items-start gap-2"
                aria-pressed="false">
                <span class="min-w-0 flex-1">
                    <span class="extra-mode-title block text-sm font-semibold text-gray-700 leading-tight">Full Amenities</span>
                    <span id="extraModeFullDesc" class="extra-mode-desc block text-[11px] text-gray-500 leading-tight mt-0.5 truncate sm:whitespace-normal sm:overflow-visible">{{ $amenitiesDesc }}</span>
                </span>
            </button>
            <button type="button" id="extraModeBreakfast"
                class="rounded-xl border border-gray-200 bg-white text-left px-3 py-2 transition-all duration-200 cursor-pointer hover:border-[#017249]/40 flex items-start gap-2"
                aria-pressed="false">
                <span class="min-w-0 flex-1">
                    <span class="extra-mode-title block text-sm font-semibold text-gray-700 leading-tight">Extra Breakfast</span>
                    <span id="extraModeBreakfastDesc" class="extra-mode-desc block text-[11px] text-gray-500 leading-tight mt-0.5 truncate sm:whitespace-normal sm:overflow-visible">{{ $extraBreakfastDesc }}</span>
                </span>
            </button>
        </div>
    </div>

    <!-- Extra Items -->
    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/10">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Extra Items
            </h3>
            <span id="amenitiesCount" class="text-xs bg-[#017249] text-white px-2 py-0.5 rounded-full font-bold">0</span>
        </div>
        <p class="text-xs text-gray-600 mb-3">Add-ons to enhance your glamping experience</p>
        <button type="button" onclick="openAmenitiesModal()"
            class="w-full bg-gradient-to-r from-[#017249]/10 to-[#0b5a3e]/10 hover:from-[#017249]/20 hover:to-[#0b5a3e]/20 text-[#017249] rounded-xl py-2.5 text-sm font-semibold transition-all duration-300 flex items-center justify-center gap-2 border border-[#017249]/20 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Select Extra Items
        </button>
    </div>
</section>
