{{-- Policy Section --}}
<section class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden"
    data-aos="fade-up"
    data-aos-duration="800"
    aria-labelledby="kebijakan-heading">
    <div class="p-4 sm:p-6 md:p-8 lg:p-10">
        <x-section-heading
            id="kebijakan-heading"
            title="POLICY"
            titleClass="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#017249] tracking-wider" />

        @php
        // Data in array - easy to update
        $rescheduleRules = [
        ['time' => 'Before D-7', 'fee' => 'Free / no additional fee'],
        ['time' => 'D-7 to D-3', 'fee' => 'Additional fee', 'amount' => 'IDR 150,000', 'unit' => '/tent'],
        ['time' => 'D-2 to D-1', 'fee' => 'Additional fee', 'amount' => 'IDR 250,000', 'unit' => '/tent'],
        ['time' => 'D-Day', 'fee' => 'Additional fee according to tent price'],
        ];

        $cancellationRules = [
        ['time' => 'Before D-7', 'percentage' => '25%'],
        ['time' => 'D-7 to D-4', 'percentage' => '50%'],
        ['time' => 'D-3 to D-2', 'percentage' => '75%'],
        ['time' => 'D-1 to D-Day', 'percentage' => '100%'],
        ];
        @endphp

        {{-- Policy Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 xl:gap-12 mb-6 sm:mb-8 md:mb-12"
            style="font-family: 'Poppins', sans-serif;">

            {{-- Rescheduling --}}
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border-2 border-gray-100">
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-[#017249] mb-3 sm:mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Reschedule
                </h3>
                <ul class="space-y-3 sm:space-y-4">
                    @foreach($rescheduleRules as $rule)
                    <li class="flex items-start gap-2 sm:gap-3">
                        <div class="flex-shrink-0 w-2 h-2 rounded-full bg-[#017249] mt-1.5 sm:mt-2"></div>
                        <div class="flex-1 text-xs sm:text-sm md:text-base lg:text-lg">
                            <span class="font-semibold text-gray-900">{{ $rule['time'] }}:</span>
                            <span class="text-gray-700"> {{ $rule['fee'] }}</span>
                            @isset($rule['amount'])
                            <span class="font-bold text-[#017249]"> {{ $rule['amount'] }}</span><span class="text-gray-700">{{ $rule['unit'] ?? '' }}</span>
                            @endisset
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Cancellation --}}
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border-2 border-gray-100">
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-[#017249] mb-3 sm:mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </h3>
                <ul class="space-y-3 sm:space-y-4">
                    @foreach($cancellationRules as $rule)
                    <li class="flex items-start gap-2 sm:gap-3">
                        <div class="flex-shrink-0 w-2 h-2 rounded-full bg-[#017249] mt-1.5 sm:mt-2"></div>
                        <div class="flex-1 text-xs sm:text-sm md:text-base lg:text-lg">
                            <span class="font-semibold text-gray-900">{{ $rule['time'] }}:</span>
                            <span class="text-gray-700"> Fee </span>
                            <span class="font-bold text-[#017249]">{{ $rule['percentage'] }}</span>
                            <span class="text-gray-700"> per tent</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Information Box --}}
        <div class="bg-gradient-to-br from-[#017249]/5 to-[#017249]/10 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 border-2 border-[#017249]/20"
            style="font-family: 'Poppins', sans-serif;">
            <h4 class="flex items-center gap-2 text-[#017249] font-bold text-base sm:text-lg md:text-xl mb-3 sm:mb-5">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Information
            </h4>
            <ul class="space-y-3 sm:space-y-4 text-xs sm:text-sm md:text-base lg:text-lg text-gray-700 mb-4 sm:mb-6">
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-2 h-2 rounded-full bg-[#017249] mt-1.5 sm:mt-2"></div>
                    <div>
                        <span class="text-[#017249] font-semibold">Overpayment/refund</span> will be transferred within <span class="text-[#017249] font-semibold">14 working days</span> after the recipient's bank account details are provided.
                    </div>
                </li>
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-2 h-2 rounded-full bg-[#017249] mt-1.5 sm:mt-2"></div>
                    <div>
                        Refunds <span class="text-[#017249] font-semibold">due to failed reservations</span> will be processed within <span class="text-[#017249] font-semibold">3 working days</span> after the recipient's bank account details are provided.
                    </div>
                </li>
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-2 h-2 rounded-full bg-[#017249] mt-1.5 sm:mt-2"></div>
                    <div>
                        In case of circumstances beyond control (e.g., <span class="text-[#017249] font-semibold">government restrictions/lockdown</span> causing area closure), refunds are subject to a <span class="text-[#017249] font-semibold">50%</span> deduction.
                    </div>
                </li>
            </ul>
            <p class="text-center italic text-gray-600 text-xs sm:text-sm md:text-base lg:text-lg border-t-2 border-[#017249]/20 pt-3 sm:pt-5">
                In all other cases, terms & conditions apply as written
            </p>
        </div>
    </div>
</section>