<!-- Preview Detail Pesanan (Sticky Sidebar) -->
<section class="bg-white rounded-xl shadow-md p-5 border-2 border-[#017249]/10">
    <h3 class="text-lg font-bold text-[#017249] mb-5 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Booking Preview
    </h3>

    <!-- Info Preview Cards -->
    <div class="space-y-3 mb-5">
        <!-- Check-In/Out Date -->
        <div class="bg-gradient-to-r from-[#017249]/5 to-transparent rounded-xl p-3">
            <div class="text-[10px] text-gray-500 mb-1">Check-in/Check-out Date</div>
            <div class="font-semibold text-gray-800 text-sm" id="previewDates">- - -</div>
        </div>

        <!-- Area & Deck -->
        <div class="bg-gradient-to-r from-[#017249]/5 to-transparent rounded-xl p-3">
            <div class="text-[10px] text-gray-500 mb-1">Area & Deck</div>
            <div class="font-semibold text-gray-800 text-sm" id="previewArea">Pineus Tilu 1 / -</div>
        </div>

        <!-- Number of Guests & Name -->
        <div class="bg-gradient-to-r from-[#017249]/5 to-transparent rounded-xl p-3">
            <div class="text-[10px] text-gray-500 mb-1">Guests & Name</div>
            <div class="font-semibold text-gray-800 text-sm" id="previewGuest">1 guest (-)</div>
        </div>
    </div>

    <!-- Extra Items Preview -->
    <div class="mb-5">
        <div class="text-xs font-medium text-gray-700 mb-2">Extra Items:</div>
        <div id="previewAmenities" class="flex flex-wrap gap-1.5">
            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg">No items selected</span>
        </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-gray-200 my-4"></div>

    <!-- Price Summary -->
    <div class="bg-gradient-to-br from-[#017249]/10 to-[#0b5a3e]/5 rounded-xl p-4 border border-[#017249]/20 mb-5">
        <div class="text-xs text-gray-600 mb-1">Estimated Total Price</div>
        <div class="text-2xl font-extrabold text-[#017249] mb-3" id="previewPrice">Rp 500.000</div>
        <div class="text-[10px] text-gray-500 mb-3">*Final price will be calculated after confirmation</div>

        <div class="space-y-1.5 text-xs">
            <div class="flex justify-between text-gray-600">
                <span>Base Price:</span>
                <span id="previewBasePrice" class="font-semibold">Rp 500.000</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Extra Items:</span>
                <span id="previewAmenitiesPrice" class="font-semibold">Rp 0</span>
            </div>
            <div id="previewBreakfastRow" class="justify-between text-gray-600 hidden">
                <span id="previewExtraChargeLabel">Extra Breakfast:</span>
                <span id="previewBreakfastExtra" class="font-semibold">Rp 0</span>
            </div>
        </div>
    </div>

    <!-- Terms & Submit -->
    <div>
        <div class="flex items-start gap-2 mb-2">
            <input type="checkbox" id="agree" name="agree" required
                class="h-4 w-4 mt-0.5 text-[#017249] border-gray-300 rounded focus:ring-[#017249] cursor-pointer"
                @error('agree') aria-describedby="agreeError" @enderror>
            <label for="agree" class="text-xs text-gray-600 cursor-pointer">
                I agree to the
                <a href="{{ route('pedoman') }}#syarat-heading" class="font-semibold text-[#017249] underline underline-offset-2 hover:text-[#0b5a3e]">Terms &amp; Conditions</a>
            </label>
        </div>
        @error('agree')
            <p id="agreeError" class="text-xs text-red-600 font-medium mb-3">{{ $message }}</p>
        @enderror
        <button type="submit"
            class="w-full bg-gradient-to-r from-[#017249] to-[#0b5a3e] hover:from-[#0b5a3e] hover:to-[#017249] text-white rounded-xl py-3 font-bold text-sm shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            @if(!empty($rescheduleMode))
                Apply Reschedule
            @else
                Book Now
            @endif
        </button>
    </div>
</section>
