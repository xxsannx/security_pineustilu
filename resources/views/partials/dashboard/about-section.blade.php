<!-- About Section -->
@php
use App\Helpers\GalleryHelper;
$dashboardGalleries = GalleryHelper::getDashboardGalleries('galeri');
// Get specific images for cards
$tentImage = $dashboardGalleries->first()?->url ?? asset('images/dashboard/tenda.jpg');
$pemulihanImage = $dashboardGalleries->skip(1)->first()?->url ?? asset('images/dashboard/pemulihan.jpg');
@endphp
<section class="py-6 md:py-12 bg-white overflow-hidden w-full">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full">
        <!-- Section Header - No Card -->
        <div class="mb-6 md:mb-12" data-aos="fade-up" data-aos-duration="800">
            <div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-10">
                <div class="w-full text-center md:text-center">
                    <p class="text-[#017249] text-center leading-relaxed text-sm sm:text-base md:text-xl [line-height:1.8] mb-4">
                        Pioneer of Indonesia glamping in the mist of Rahong Pine Forest & Palayangan River in Pangalengan, Bandung
                    </p>
                </div>
            </div>
        </div>

        <!-- Feature Cards Grid -->
        <div class="w-full space-y-2 md:space-y-4">
            <!-- Natural/Tent - Image Left -->
            <div class="group" data-aos="fade-up" data-aos-duration="800">
                <div class="flex flex-col md:flex-row items-center gap-4 md:gap-12">
                    <!-- Image with blob shape -->
                    <div class="w-full md:w-1/2 flex justify-center md:justify-start">
                        <div class="relative w-full max-w-[280px] sm:max-w-[350px] md:max-w-[900px] aspect-[4/3]">
                            <div class="w-full h-full rounded-[30%_70%_70%_30%/60%_40%_60%_40%] overflow-hidden transition-all duration-500">
                                <img src="{{ $tentImage }}" alt="Glamping Tent"
                                    class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="w-full md:w-1/2 text-center md:text-left px-2 sm:px-0">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#017249] mb-2 md:mb-4"
                            style="font-family: 'Bizon', sans-serif;">NATURAL</h3>
                        <p class="text-black leading-relaxed text-sm sm:text-base md:text-lg">
                            “Back to the nature” that equipped with modern facilities, providing mental, physical & emotional boost.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ambience - Image Right -->
            <div class="group" data-aos="fade-up" data-aos-duration="800">
                <div class="flex flex-col md:flex-row-reverse items-center gap-4 md:gap-12">
                    <!-- Image with blob shape -->
                    <div class="w-full md:w-1/2 flex justify-center md:justify-end">
                        <div class="relative w-full max-w-[280px] sm:max-w-[350px] md:max-w-[900px] aspect-[4/3]">
                            <div class="w-full h-full rounded-[70%_30%_30%_70%/40%_60%_40%_60%] overflow-hidden transition-all duration-500">
                                <img src="{{ $pemulihanImage }}" alt="Ambience"
                                    class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="w-full md:w-1/2 text-center md:text-right px-2 sm:px-0">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#017249] mb-2 md:mb-4"
                            style="font-family: 'Bizon', sans-serif;">AMBIENCE</h3>
                        <p class="text-black leading-relaxed text-sm sm:text-base md:text-lg">
                            Perfect place surrounded by fresh air of pine forest & soothing sound of river.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Experience - Image Left -->
            <div class="group" data-aos="fade-up" data-aos-duration="800">
                <div class="flex flex-col md:flex-row items-center gap-4 md:gap-12">
                    <!-- Image with blob shape -->
                    <div class="w-full md:w-1/2 flex justify-center md:justify-start">
                        <div class="relative w-full max-w-[280px] sm:max-w-[350px] md:max-w-[900px] aspect-[4/3]">
                            <div class="w-full h-full rounded-[40%_60%_60%_40%/50%_50%_50%_50%] overflow-hidden transition-all duration-500">
                                <img src="{{ asset('images/dashboard/suasana.JPG') }}" alt="Experience"
                                    class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="w-full md:w-1/2 text-center md:text-left px-2 sm:px-0">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#017249] mb-2 md:mb-4"
                            style="font-family: 'Bizon', sans-serif;">EXPERIENCE</h3>
                        <p class="text-black leading-relaxed text-sm sm:text-base md:text-lg">
                            Peaceful quiet escape from the "hustle & bustle" of city life.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Togetherness - Image Right -->
            <div class="group" data-aos="fade-up" data-aos-duration="800">
                <div class="flex flex-col md:flex-row-reverse items-center gap-4 md:gap-12">
                    <!-- Image with blob shape -->
                    <div class="w-full md:w-1/2 flex justify-center md:justify-end">
                        <div class="relative w-full max-w-[280px] sm:max-w-[350px] md:max-w-[900px] aspect-[4/3]">
                            <div class="w-full h-full rounded-[40%_60%_50%_50%/45%_55%_45%_55%] overflow-hidden transition-all duration-500">
                                <img src="{{ asset('images/dashboard/apiunggun.jpg') }}" alt="Togetherness"
                                    class="w-full h-full object-cover object-bottom group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="w-full md:w-1/2 text-center md:text-right px-2 sm:px-0">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#017249] mb-2 md:mb-4"
                            style="font-family: 'Bizon', sans-serif;">TOGETHERNESS</h3>
                        <p class="text-black leading-relaxed text-sm sm:text-base md:text-lg">
                            Precious moments with family & friends under the night of starry sky.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Activities - Image Left -->
            <div class="group" data-aos="fade-up" data-aos-duration="800">
                <div class="flex flex-col md:flex-row items-center gap-4 md:gap-12">
                    <!-- Image with oval/ellipse shape -->
                    <div class="w-full md:w-1/2 flex justify-center md:justify-start">
                        <div class="relative w-full max-w-[280px] sm:max-w-[350px] md:max-w-[900px] aspect-[4/3]">
                            <div class="w-full h-full rounded-[35%_65%_60%_40%/55%_45%_55%_45%] overflow-hidden transition-all duration-500">
                                <img src="{{ asset('images/dashboard/aktifitas.JPG') }}" alt="Activities"
                                    class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="w-full md:w-1/2 text-center md:text-left px-2 sm:px-0">
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#017249] mb-2 md:mb-4"
                            style="font-family: 'Bizon', sans-serif;">ACTIVITIES</h3>
                        <p class="text-black leading-relaxed text-sm sm:text-base md:text-lg">
                            Excitement of adventure with a complete selection of outdoor activities.
                        </p>
                        <a href="{{ route('aktivitas') }}"
                            class="inline-flex items-center mt-3 text-[#017249] font-semibold underline underline-offset-4 hover:text-[#015a3a] transition-colors">
                            More Activities
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
