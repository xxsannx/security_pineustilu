<footer class="bg-white border-t border-gray-200 overflow-x-hidden w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 w-full overflow-hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 w-full" data-aos="fade-up" data-aos-duration="800">
            <!-- Logo Section -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-1 flex flex-col items-start" data-aos="fade-up" data-aos-delay="100">
                <div class="w-[160px] sm:w-[180px] md:w-[200px]">
                    <img src="{{ asset('images/dashboard/logo.png') }}" alt="Pineus Tilu Logo" class="w-full h-auto mb-3 md:mb-4 object-contain">
                    <p class="text-xs sm:text-sm md:text-base text-gray-600 text-left">© 2024. Pineus Tilu, All Rights Reserved.</p>
                </div>
            </div>

            <!-- General Questions -->
            <div class="flex flex-col items-start" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-[#017249] font-semibold mb-2 sm:mb-3 md:mb-4 text-sm md:text-base">General Questions</h3>
                <ul class="space-y-1 text-xs sm:text-sm md:text-base text-left">
                    <li><a href="{{ route('reschedule') }}" class="text-gray-600 hover:text-[#017249] active:text-[#015a3a] transition-colors">Rescheduling</a></li>
                    <li><a href="{{ route('cancellation') }}" class="text-gray-600 hover:text-[#017249] active:text-[#015a3a] transition-colors">Cancellation</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="flex flex-col items-start" data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-[#017249] font-semibold mb-2 sm:mb-3 md:mb-4 text-sm md:text-base">Contact</h3>
                <div class="space-y-2 md:space-y-3 text-xs sm:text-sm md:text-base text-gray-600">
                    <div class="flex items-center justify-start gap-2 sm:gap-3">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <span>+62 877-3548-2327</span>
                    </div>
                    <div class="flex items-center justify-start gap-2 sm:gap-3">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <span>+62 812-2041-3424</span>
                    </div>
                    <div class="flex items-center justify-start gap-2 sm:gap-3">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <span>pineust@gmail.com</span>
                    </div>
                    <div class="flex items-center justify-start gap-2 sm:gap-3">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>
                        <span>pineustilu</span>
                    </div>
                </div>
            </div>

            <!-- Suggestions -->
            <div class="flex flex-col items-start sm:col-span-2 lg:col-span-1" data-aos="fade-up" data-aos-delay="400">
                <h3 class="text-[#017249] font-semibold mb-2 sm:mb-3 md:mb-4 text-sm md:text-base">Suggestions</h3>
                <p class="text-gray-600 text-xs sm:text-sm md:text-base mb-2 md:mb-3">Enter your email address</p>
                <div class="space-y-2 w-full max-w-xs sm:max-w-sm">
                    <input type="email" placeholder="Example: Email@Domain.Com" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-2xl text-xs sm:text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-[#017249]">
                    <button class="w-full bg-[#017249] hover:bg-[#015a3a] active:bg-[#014a2e] text-white py-2 px-4 rounded-2xl text-xs sm:text-sm md:text-base font-medium transition duration-300">
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</footer>