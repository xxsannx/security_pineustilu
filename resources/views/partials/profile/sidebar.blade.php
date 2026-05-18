{{-- Profile Sidebar --}}
<div class="lg:col-span-1" data-aos="fade-right">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl p-4 sm:p-6 md:p-8 sticky top-24">
        {{-- Profile Avatar --}}
        <div class="flex flex-col items-center mb-4 sm:mb-6 md:mb-8">
            <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full bg-gradient-to-br from-[#017249] to-[#015a3a] flex items-center justify-center text-white text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4 shadow-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 font-poppins text-center">
                {{ auth()->user()->name }}
            </h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 font-poppins text-center break-all">
                {{ auth()->user()->email }}
            </p>
        </div>

        {{-- Mobile Dropdown Toggle Button --}}
        <button id="mobileMenuToggle" 
                class="lg:hidden w-full flex items-center justify-between px-4 sm:px-5 py-3 sm:py-3.5 text-white bg-[#017249] rounded-lg sm:rounded-xl transition-all duration-300 hover:shadow-lg font-semibold font-poppins cursor-pointer mb-3 sm:mb-4 text-sm sm:text-base">
            <span class="flex items-center">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Menu
            </span>
            <svg id="dropdownArrow" class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- Navigation Menu --}}
        <nav id="navigationMenu" class="space-y-1 sm:space-y-2 hidden lg:block opacity-0 lg:opacity-100 scale-95 lg:scale-100 transition-all duration-300 transform origin-top">
            <a href="{{ route('profile', ['tab' => 'profile']) }}"
               class="flex items-center px-3 sm:px-4 md:px-5 py-2.5 sm:py-3 md:py-3.5 {{ ($currentTab ?? 'profile') === 'profile' ? 'text-white bg-[#017249] shadow-lg' : 'text-gray-700 hover:bg-[#f0fdf4]' }} rounded-lg sm:rounded-xl transition-all duration-300 hover:scale-105 font-semibold font-poppins cursor-pointer transform text-xs sm:text-sm md:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile
            </a>
            <a href="{{ route('profile', ['tab' => 'bookings']) }}"
               class="flex items-center px-3 sm:px-4 md:px-5 py-2.5 sm:py-3 md:py-3.5 {{ ($currentTab ?? 'profile') === 'bookings' ? 'text-white bg-[#017249] shadow-lg' : 'text-gray-700 hover:bg-[#f0fdf4]' }} rounded-lg sm:rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-md font-medium font-poppins cursor-pointer transform text-xs sm:text-sm md:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Booking
            </a>
            <a href="{{ route('profile', ['tab' => 'reschedule']) }}"
               class="flex items-center px-3 sm:px-4 md:px-5 py-2.5 sm:py-3 md:py-3.5 {{ ($currentTab ?? 'profile') === 'reschedule' ? 'text-white bg-[#017249] shadow-lg' : 'text-gray-700 hover:bg-[#f0fdf4]' }} rounded-lg sm:rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-md font-medium font-poppins cursor-pointer transform text-xs sm:text-sm md:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Reschedule
            </a>
            <a href="{{ route('profile', ['tab' => 'cancellation']) }}"
               class="flex items-center px-3 sm:px-4 md:px-5 py-2.5 sm:py-3 md:py-3.5 {{ ($currentTab ?? 'profile') === 'cancellation' ? 'text-white bg-[#017249] shadow-lg' : 'text-gray-700 hover:bg-[#f0fdf4]' }} rounded-lg sm:rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-md font-medium font-poppins cursor-pointer transform text-xs sm:text-sm md:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancellation
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center px-3 sm:px-4 md:px-5 py-2.5 sm:py-3 md:py-3.5 text-red-600 hover:bg-red-50 rounded-lg sm:rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-md font-medium font-poppins cursor-pointer transform text-xs sm:text-sm md:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign Out
                </button>
            </form>
        </nav>
    </div>
</div>