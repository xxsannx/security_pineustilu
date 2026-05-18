{{-- Insurance Information Section --}}
<section class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden mb-4 sm:mb-6 md:mb-8" 
         data-aos="fade-up" 
         data-aos-duration="800"
         aria-labelledby="asuransi-heading">
    <div class="p-4 sm:p-6 md:p-8 lg:p-10">
        <header class="text-center mb-4 sm:mb-6 md:mb-8 lg:mb-12">
            <x-section-heading
                id="asuransi-heading"
                title="INSURANCE INFORMATION"
                wrapperClass="text-center"
                titleClass="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#017249] tracking-wider" />
            <x-section-heading
                as="h3"
                title="CONSENT STATEMENT"
                wrapperClass="text-center"
                titleClass="text-base sm:text-lg md:text-xl lg:text-2xl font-extrabold text-[#017249] tracking-wider" />
        </header>

        @php
            // Insurance data - single source of truth
            $insurance = [
                'provider' => 'ASURANSI SYARIAH AMANAH GITHA',
                'policyNumber' => '809000050100188',
                'coverage' => [
                    [
                        'description' => 'Death due to accident (within 24 hours)',
                        'amount' => 'IDR 15,000,000',
                    ],
                    [
                        'description' => 'Death at tourism location not due to accident',
                        'amount' => 'IDR 3,000,000',
                    ],
                    [
                        'description' => 'Permanent disability according to percentage (%)',
                        'note' => 'according to general policy terms',
                        'amount' => 'maximum IDR 20,000,000',
                    ],
                    [
                        'description' => 'Medical treatment costs due to accident (maximum)',
                        'amount' => 'IDR 3,000,000',
                    ],
                ],
            ];
        @endphp

        {{-- Content --}}
        <div class="max-w-4xl mx-auto" style="font-family: 'Poppins', sans-serif;">
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 border-2 border-gray-100 mb-4 sm:mb-6 md:mb-8">
                <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700 leading-relaxed text-justify mb-3 sm:mb-4">
                    Starting January 2022, every visitor camping at 
                    <span class="font-bold text-[#017249]">Pineus Tilu Camping Ground</span> 
                    must provide the following statement. As the person responsible for myself and my group, 
                    I declare that if any accident/disaster/misfortune occurs while camping at 
                    <span class="font-bold text-[#017249]">Pineus Tilu Camping Ground</span>, 
                    the management is not responsible, except if we are entitled to receive insurance coverage 
                    provided in cooperation with Perhutani according to the following coverage details:
                </p>

                <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700 leading-relaxed text-justify">
                    Visitors receive accident insurance protection from 
                    <span class="font-bold text-[#017249] uppercase">{{ $insurance['provider'] }}</span> 
                    Master Policy No. <span class="font-bold text-[#017249]">{{ $insurance['policyNumber'] }}</span> 
                    for accidents from entering the gate until leaving the area. Insurance protection is as follows:
                </p>
            </div>

            <div class="bg-gradient-to-br from-[#017249]/5 to-[#017249]/10 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 border-2 border-[#017249]/20">
                <h4 class="text-sm sm:text-base md:text-lg font-bold text-[#017249] mb-4 sm:mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Insurance Coverage
                </h4>
                <ol class="space-y-3 sm:space-y-4">
                    @foreach($insurance['coverage'] as $index => $item)
                        <li class="flex items-start gap-2 sm:gap-4">
                            <div class="flex-shrink-0 w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-[#017249] text-white flex items-center justify-center font-bold text-xs sm:text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 pt-0.5 sm:pt-1 text-xs sm:text-sm md:text-base lg:text-lg">
                                <span class="font-semibold text-gray-900">{{ $item['description'] }}:</span>
                                @isset($item['note'])
                                    <span class="text-gray-700"> {{ $item['note'] }}, </span>
                                @endisset
                                <span class="font-bold text-[#017249]">{{ $item['amount'] }}</span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>