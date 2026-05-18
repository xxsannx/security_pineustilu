<x-layouts.auth>
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8 sm:py-12 relative z-10">
        <div class="w-full max-w-md mx-auto">
            <!-- Card Container -->
            <div
                class="bg-white rounded-3xl shadow-xl border-2 border-gray-100 p-6 sm:p-8 lg:p-10 space-y-6 backdrop-blur-sm relative">
                {{-- Back Button - Top Left Corner --}}
                <a href="{{ route('login') }}"
                    class="absolute top-4 left-4 sm:top-6 sm:left-6 z-20 inline-flex items-center gap-2 text-gray-600 hover:text-[#017249] transition-all duration-200 group"
                    aria-label="Go back to login">
                    <div
                        class="p-2 sm:p-2.5 bg-gray-100 rounded-xl shadow-md border border-gray-200 group-hover:shadow-lg group-hover:bg-[#017249]/10 group-hover:border-[#017249]/20 group-hover:-translate-x-1 transition-all duration-200">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 group-hover:text-[#017249] transition-colors duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium group-hover:text-[#017249] transition-colors duration-200">Back</span>
                </a>

                <!-- Header -->
                <div class="text-center space-y-3 sm:space-y-4 pt-10 sm:pt-12">
                    <div class="flex justify-center mb-3 sm:mb-4">
                        <div class="p-3 sm:p-4 bg-[#017249]/10 rounded-2xl">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-[#017249]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#017249]"
                        style="font-family: 'Bizon', sans-serif;">{{ __('Forgot Password') }}</h1>
                    <p class="text-gray-600 text-sm sm:text-base">{{ __('Enter your email to receive a password reset link') }}</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-700">{{ __('Email Address') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                <div
                                    class="bg-[#017249]/10 p-1.5 sm:p-2 rounded-2xl group-focus-within:bg-[#017249]/20 transition-colors duration-200">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                autofocus autocomplete="email" placeholder="email@example.com"
                                class="block w-full pl-14 sm:pl-16 pr-4 py-3 sm:py-3.5 text-sm sm:text-base text-gray-900 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#017249]/20 focus:border-[#017249] transition-all duration-200 placeholder-gray-400 hover:border-gray-300 cursor-text" />
                        </div>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" data-test="email-password-reset-link-button"
                        class="w-full bg-gradient-to-r from-[#017249] to-[#015a3a] hover:from-[#015a3a] hover:to-[#014d35] text-white text-sm sm:text-base font-semibold py-3 sm:py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-[#017249]/30 flex items-center justify-center gap-2 group cursor-pointer">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ __('Send Reset Link') }}</span>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="text-center pt-3 sm:pt-4 pb-2">
                    <div class="inline-flex flex-wrap items-center justify-center gap-1 text-xs sm:text-sm">
                        <span class="text-gray-600">{{ __('Remember your password?') }}</span>
                        <a href="{{ route('login') }}"
                            class="font-semibold text-[#017249] hover:text-[#015a3a] transition-all duration-200 hover:underline inline-flex items-center gap-1 group">
                            <span>{{ __('Log in') }}</span>
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
