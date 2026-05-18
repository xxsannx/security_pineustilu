<!-- Modal Order Details -->
<div id="orderDetailsModal" class="fixed inset-0 backdrop-blur-sm bg-black/20 z-[9999] items-center justify-center p-4 hidden"
    role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl border-2 border-gray-100">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b-2 border-gray-100 p-6 md:p-8 flex justify-between items-center z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-br from-[#017249] to-[#015a3a] p-3 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h3 id="orderModalTitle" class="text-2xl md:text-3xl font-bold text-gray-800" style="font-family: 'Bizon', sans-serif;">Order Details</h3>
                    <p class="text-gray-500 text-sm mt-1" id="orderModalSubtitle">Complete information about your booking</p>
                </div>
            </div>
            <button type="button" id="closeOrderDetailsBtn"
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
            <div id="orderLoading" class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
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
            <div id="orderError" class="hidden bg-white rounded-2xl p-6 shadow-lg border-2 border-red-100">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-bold text-gray-800">Failed to load order details</div>
                        <div id="orderErrorText" class="text-sm text-gray-600 mt-1">Please try again.</div>
                    </div>
                </div>
            </div>

            <!-- Content State -->
            <div id="orderContent" class="hidden space-y-6">
                <!-- Booking ID -->
                <div class="text-center">
                    <p class="text-gray-600 text-sm font-semibold" id="bookingId">#GLAMPING00000000</p>
                </div>

                <!-- Progress Stepper -->
                <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-lg border-2 border-gray-100 overflow-x-auto">
                    <div class="flex items-center justify-between relative min-w-[320px]">
                        <!-- Booking -->
                        <div class="flex flex-col items-center z-10 flex-1">
                            <div id="statusBooking" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300 bg-gray-300 text-gray-600">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-semibold text-center text-gray-600">Booking</span>
                        </div>

                        <!-- Progress Line 1 -->
                        <div class="absolute top-4 sm:top-5 left-[10%] right-[10%] h-0.5 bg-gray-300 -z-0">
                            <div id="progressLine1" class="h-full bg-[#017249] transition-all duration-500 w-0"></div>
                        </div>

                        <!-- Payment -->
                        <div class="flex flex-col items-center z-10 flex-1">
                            <div id="statusPayment" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300 bg-gray-300 text-gray-600">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-semibold text-center text-gray-600">Payment</span>
                        </div>

                        <!-- Confirmed -->
                        <div class="flex flex-col items-center z-10 flex-1">
                            <div id="statusConfirmed" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300 bg-gray-300 text-gray-600">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-semibold text-center text-gray-600">Confirmed</span>
                        </div>

                        <!-- In Progress -->
                        <div class="flex flex-col items-center z-10 flex-1">
                            <div id="statusProgress" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300 bg-gray-300 text-gray-600">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-semibold text-center leading-tight text-gray-600"><span class="hidden sm:inline">In </span>Progress</span>
                        </div>

                        <!-- Completed -->
                        <div class="flex flex-col items-center z-10 flex-1">
                            <div id="statusCompleted" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center mb-1 sm:mb-2 transition-all duration-300 bg-gray-300 text-gray-600">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-semibold text-center text-gray-600"><span class="hidden sm:inline">Com</span>pleted</span>
                        </div>
                    </div>
                </div>

                <!-- Date Section -->
                <div id="dateSection" class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                    <!-- Will be populated dynamically -->
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Guest Details -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                        <h4 class="font-bold text-base text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-[#017249]">Guest Details</span>
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600">Full Name</span>
                                <span class="text-gray-900 font-medium" id="guestName">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600">Phone</span>
                                <span class="text-gray-900 font-medium" id="guestPhone">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600">Email</span>
                                <span class="text-gray-900 font-medium text-right break-all" id="guestEmail">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600">Guests</span>
                                <span class="text-gray-900 font-medium" id="guestCount">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Reservation Details -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                        <h4 class="font-bold text-base text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="text-[#017249]">Reservation Details</span>
                        </h4>
                        <div id="reservationDetails" class="space-y-3 text-sm">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Add-ons Section (if available) -->
                <div id="addonsSection" class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 hidden">
                    <h4 class="font-bold text-base text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="text-[#017249]">Add-ons</span>
                    </h4>
                    <ul id="addonsList" class="space-y-2 text-sm">
                        <!-- Will be populated dynamically -->
                    </ul>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gradient-to-br from-[#017249] to-[#015a3a] rounded-2xl p-6 shadow-lg text-white">
                    <h4 class="font-bold text-base mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Payment Summary
                    </h4>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-white/90">Subtotal</span>
                            <span class="font-medium" id="paymentSubtotal">Rp. 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/90">Additional Fees</span>
                            <span class="font-medium" id="paymentFees">Rp. 0</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/20">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-2xl font-bold" id="paymentTotal">Rp. 0</span>
                        </div>
                    </div>
                </div>

                <!-- Reschedule & Cancel Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a href="{{ route('reschedule') }}"
                        class="flex-1 px-6 py-3 bg-white text-[#017249] border-2 border-[#017249] rounded-2xl hover:bg-[#017249] hover:text-white font-semibold transition-all duration-300 font-poppins text-sm flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                        </svg>
                        Reschedule
                    </a>
                    <a href="{{ route('cancellation') }}"
                        class="flex-1 px-6 py-3 bg-red-500 text-white rounded-2xl hover:bg-red-600 font-semibold transition-all duration-300 font-poppins text-sm flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="button" id="printOrderBtn"
                        class="flex-1 px-6 py-3 bg-white text-[#017249] border-2 border-[#017249] rounded-2xl hover:bg-[#017249] hover:text-white font-semibold transition-all duration-300 font-poppins text-sm flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                    <a id="contactAdminBtn" href="https://wa.me/6281234567890" target="_blank"
                        class="flex-1 px-6 py-3 bg-[#25D366] text-white rounded-2xl hover:bg-[#1fa855] font-semibold transition-all duration-300 font-poppins text-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Contact Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
