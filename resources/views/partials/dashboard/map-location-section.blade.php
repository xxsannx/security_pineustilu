<!-- Map & Location Section Combined -->
<section class="pt-6 md:pt-8 pb-8 md:pb-10 bg-white overflow-hidden w-full">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full">
        <!-- Section Title -->
        <div class="text-center mb-6 md:mb-12" data-aos="fade-down" data-aos-duration="800">
            <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold text-[#017249] mb-2 md:mb-3" style="font-family: 'Bizon', sans-serif;">MAP</h2>
            <p class="text-sm sm:text-base md:text-lg max-w-2xl mx-auto">
                Find our location and explore the camping ground area
            </p>
        </div>

        <div class="space-y-4 md:space-y-6">
            <!-- Map (full width, above) -->
            <div data-aos="fade-up" data-aos-duration="800">
                <div class="bg-white rounded-2xl p-3 md:p-6 shadow-xl">

                    <div class="relative rounded-2xl overflow-hidden">
                        <img src="{{ asset('images/dashboard/PTDenahNew.svg') }}"
                            alt="Pineus Tilu Camp Ground Map"
                            class="w-full object-cover rounded-2xl">
                    </div>
                </div>
            </div>

            <div class="text-center mb-6 md:mb-12" data-aos="fade-down" data-aos-duration="800">
                <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold text-[#017249] mb-2 md:mb-3" style="font-family: 'Bizon', sans-serif;">LOCATION</h2>
            </div>

            <!-- Google Maps Embed -->
            <div data-aos="fade-up" data-aos-duration="800">
                <div class="bg-white rounded-2xl overflow-hidden shadow-xl">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.8579874598244!2d107.54241099999999!3d-7.1819655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e689144cb1ebb5d%3A0x5e98bae230f55aaf!2sPineus%20Tilu%20Camp%20Ground!5e0!3m2!1sen!2sid!4v1732528234567!5m2!1sen!2sid"
                        class="w-full h-[300px] md:h-[400px] lg:h-[450px]"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Pineus Tilu Camp Ground Location Map">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>