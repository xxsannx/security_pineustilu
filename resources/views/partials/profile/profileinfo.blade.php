{{-- Profile Information Card --}}
<div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl p-4 sm:p-6 md:p-8" data-aos="fade-up" data-aos-delay="100">
    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 md:mb-8 font-poppins">
        Personal Profile
    </h2>

    <div class="space-y-4 sm:space-y-6">
        {{-- Profile Section --}}
        <div class="border-b border-gray-200 pb-3 sm:pb-4">
            <h3 class="text-sm sm:text-base md:text-lg font-semibold text-gray-700 mb-3 sm:mb-4 font-poppins">Profile</h3>

            <div class="flex items-center justify-between py-2 sm:py-3 hover:bg-gray-50 px-2 sm:px-4 rounded-xl sm:rounded-2xl transition-colors">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm text-gray-500 font-poppins">Name</p>
                    <p class="text-sm sm:text-base font-medium text-gray-800 mt-0.5 sm:mt-1 font-poppins" data-user-name>
                        {{ auth()->user()->name }}
                    </p>
                </div>
                <button type="button" data-open-modal="name"
                    class="text-[#017249] hover:text-[#015a3a] font-semibold text-xs sm:text-sm transition-colors font-poppins cursor-pointer">
                    Change
                </button>
            </div>
        </div>

        {{-- Contact Section --}}
        <div class="border-b border-gray-200 pb-3 sm:pb-4">
            <h3 class="text-sm sm:text-base md:text-lg font-semibold text-gray-700 mb-3 sm:mb-4 font-poppins">Contact</h3>

            {{-- Email --}}
            <div class="flex items-center justify-between py-2 sm:py-3 hover:bg-gray-50 px-2 sm:px-4 rounded-xl sm:rounded-2xl transition-colors">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-500 font-poppins">Email</p>
                    <p class="text-sm sm:text-base font-medium text-gray-800 mt-0.5 sm:mt-1 font-poppins truncate" data-user-email>
                        {{ auth()->user()->email }}
                    </p>
                </div>
                <button type="button" data-open-modal="email"
                    class="text-[#017249] hover:text-[#015a3a] font-semibold text-xs sm:text-sm transition-colors font-poppins cursor-pointer ml-2">
                    Change
                </button>
            </div>

            {{-- Phone Number --}}
            <div class="flex items-center justify-between py-2 sm:py-3 hover:bg-gray-50 px-2 sm:px-4 rounded-xl sm:rounded-2xl transition-colors">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm text-gray-500 font-poppins">Phone Number</p>
                    <p class="text-sm sm:text-base font-medium text-gray-800 mt-0.5 sm:mt-1 font-poppins">
                        @if (auth()->user()->phone)
                            <span class="inline-flex items-center gap-1" data-user-phone>
                                <span class="text-gray-600"
                                    data-user-country-code>{{ auth()->user()->country_code }}</span>
                                <span data-user-phone-number>{{ auth()->user()->phone }}</span>
                            </span>
                        @else
                            <span class="text-gray-400" data-user-phone>Not set</span>
                        @endif
                    </p>
                </div>
                <button type="button" data-open-modal="phone"
                    class="text-[#017249] hover:text-[#015a3a] font-semibold text-xs sm:text-sm transition-colors font-poppins cursor-pointer">
                    Change
                </button>
            </div>

            {{-- Password --}}
            <div class="flex items-center justify-between py-2 sm:py-3 hover:bg-gray-50 px-2 sm:px-4 rounded-xl sm:rounded-2xl transition-colors">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm text-gray-500 font-poppins">Password</p>
                    <p class="text-sm sm:text-base font-medium text-gray-800 mt-0.5 sm:mt-1 font-poppins">
                        ••••••••••••
                    </p>
                </div>
                <button type="button" data-open-modal="password"
                    class="text-[#017249] hover:text-[#015a3a] font-semibold text-xs sm:text-sm transition-colors font-poppins cursor-pointer">
                    Change
                </button>
            </div>
        </div>
    </div>
</div>
