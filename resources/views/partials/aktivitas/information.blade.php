<section class="w-full mt-6 sm:mt-8 md:mt-10 mb-6 sm:mb-8 md:mb-10" data-aos="fade-up" data-aos-duration="800">
    <div class="max-w-6xl mx-auto">
        <div class="bg-[#017249] rounded-2xl shadow-xl p-4 sm:p-6 md:p-8">
            <h2 class="text-center text-xl sm:text-2xl md:text-3xl font-bold text-white tracking-wider mb-4 sm:mb-5 md:mb-6"
                style="font-family: 'Bizon', sans-serif;">INFORMATION</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8 text-white">
                <div>
                    <h3 class="text-sm sm:text-base md:text-lg font-bold text-white mb-2 sm:mb-3">{{ $information['pickup']['title'] ?? 'PICKUP & DROP-OFF SERVICE' }}</h3>
                    <p class="text-xs sm:text-sm md:text-base leading-relaxed">
                        Except for White Water Rafting & Offroad, pickup/drop-off fee from the camping area to the outbound arena
                        is charged at
                        <strong>{{ ($information['pickup']['price_text'] ?? 'Rp 200.000') . '/car' }}</strong>, maximum 10 people.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm sm:text-base md:text-lg font-bold text-white mb-2 sm:mb-3">{{ $information['cancellation']['title'] ?? 'CANCELLATION' }}</h3>
                    <p class="text-xs sm:text-sm md:text-base leading-relaxed">
                        {{ $information['cancellation']['body'] ?? 'For Team Building & Offroad, registration must be made no later than D‑3 (3 days before the event). If cancellation is made on the day of the event, 50% of the fee is non-refundable. Refunds are processed within a maximum of 14 business days.' }}
                    </p>
                </div>
            </div>

            <!-- bottom row: INSURANCE (full width) -->
            <div class="mt-4 sm:mt-6 md:mt-8 text-white">
                <h3 class="text-sm sm:text-base md:text-lg font-bold text-white mb-2 sm:mb-3">{{ $information['insurance']['title'] ?? 'INSURANCE' }}</h3>
                <p class="text-xs sm:text-sm md:text-base leading-relaxed mb-2 sm:mb-3">{{ $information['insurance']['intro'] ?? 'All participants of White Water Rafting, Paintball, Flying Fox, Offroad, ATV activities are covered by insurance:' }}</p>
                <ul class="text-xs sm:text-sm md:text-base list-none space-y-1 sm:space-y-2">
                    @foreach(($information['insurance']['items'] ?? []) as $insuranceItem)
                        <li>{{ $insuranceItem['label'] ?? '' }}: <strong>{{ $insuranceItem['amount_text'] ?? '-' }}</strong></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
