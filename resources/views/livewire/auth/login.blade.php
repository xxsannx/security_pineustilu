<x-layouts.auth>
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8 sm:py-12 relative z-10">
        <div class="w-full max-w-md mx-auto">
            <!-- Card Container -->
            <div
                class="bg-white rounded-3xl shadow-xl border-2 border-gray-100 p-6 sm:p-8 lg:p-10 space-y-6 backdrop-blur-sm relative">
                {{-- Back Button - Top Left Corner --}}
                <a href="{{ url('/') }}"
                    class="absolute top-4 left-4 sm:top-6 sm:left-6 z-20 inline-flex items-center gap-2 text-gray-600 hover:text-[#017249] transition-all duration-200 group"
                    aria-label="Go back to home">
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
                <!-- Logo & Title -->
                <div class="text-center space-y-3 sm:space-y-4 pt-10 sm:pt-12">
                    <div class="flex justify-center mb-3 sm:mb-4">
                        <img src="{{ asset('images/dashboard/logo.png') }}" alt="Pineus Tilu Logo"
                            class="h-24 sm:h-28 lg:h-32 w-auto object-contain">
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#017249]"
                        style="font-family: 'Bizon', sans-serif;">Welcome</h1>
                    <p class="text-black text-base sm:text-base">Log in to continue your glamping experience</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <!-- Error Message -->
                @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-700">Email</label>
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
                                autofocus autocomplete="email" placeholder="email@domain.com"
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

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <label for="password"
                            class="block text-xs sm:text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                <div
                                    class="bg-[#017249]/10 p-1.5 sm:p-2 rounded-2xl group-focus-within:bg-[#017249]/20 transition-colors duration-200">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#017249]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <input id="password" name="password" type="password" required
                                autocomplete="current-password" placeholder="Enter password"
                                class="block w-full pl-14 sm:pl-16 pr-11 sm:pr-12 py-3 sm:py-3.5 text-sm sm:text-base text-gray-900 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#017249]/20 focus:border-[#017249] transition-all duration-200 placeholder-gray-400 hover:border-gray-300 cursor-text" />
                            <button type="button" data-toggle-password="password"
                                class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center hover:scale-110 transition-transform duration-200 cursor-pointer"
                                aria-label="Toggle password visibility">
                                <svg id="eye-icon-password" class="w-5 h-5 text-gray-400 hover:text-gray-600"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
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

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center group cursor-pointer">
                            <input id="remember" name="remember" type="checkbox"
                                {{ old('remember') ? 'checked' : '' }}
                                class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-[#017249] focus:ring-[#017249] border-gray-300 rounded cursor-pointer transition-all duration-200" />
                            <label for="remember"
                                class="ml-2 block text-xs sm:text-sm text-gray-600 group-hover:text-gray-800 cursor-pointer transition-colors duration-200">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs sm:text-sm text-[#017249] hover:text-[#015a3a] font-semibold transition-all duration-200 hover:underline">
                            Forgot password?
                        </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#017249] to-[#015a3a] hover:from-[#015a3a] hover:to-[#014d35] text-white text-sm sm:text-base font-semibold py-3 sm:py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-[#017249]/30 flex items-center justify-center gap-2 group cursor-pointer">
                        <span>Log In</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-5 sm:my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t-2 border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs sm:text-sm">
                        <span class="px-3 sm:px-4 bg-white text-gray-400 font-medium">Or log in with</span>
                    </div>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('google.redirect') }}"
                    class="w-full flex items-center justify-center gap-2 sm:gap-3 bg-white border-2 border-gray-200 hover:border-[#017249] text-gray-700 text-sm sm:text-base font-semibold py-3 sm:py-3.5 px-4 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 group cursor-pointer">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-110 transition-transform duration-200"
                        viewBox="0 0 24 24" fill="none">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    <span>Log in with Google</span>
                </a>

                <!-- Register Link -->
                @if (Route::has('register'))
                <div class="text-center pt-3 sm:pt-4 pb-2">
                    <div class="inline-flex flex-wrap items-center justify-center gap-1 text-xs sm:text-sm">
                        <span class="text-gray-600">Don't have an account?</span>
                        <a href="{{ route('register') }}"
                            class="font-semibold text-[#017249] hover:text-[#015a3a] transition-all duration-200 hover:underline inline-flex items-center gap-1 group">
                            <span>Register now</span>
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.auth>