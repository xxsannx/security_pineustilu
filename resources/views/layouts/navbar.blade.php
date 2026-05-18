<!-- Navbar -->
@php
    // Treat landing page and /dashboard as the same “hero page” (navbar hidden until scroll)
    $isHomePage = request()->routeIs('home', 'dashboard') || request()->is('/') || request()->is('dashboard');

    // Be tolerant of trailing slashes / subpaths
    $isMorikafePage = request()->routeIs('morikafe') || request()->is('morikafe') || request()->is('morikafe/*');

    $shouldHideNavbar = $isHomePage || $isMorikafePage;
@endphp
<nav id="main-navbar" class="bg-white shadow-md fixed w-full top-0 z-50 transition-transform duration-300 {{ $shouldHideNavbar ? '-translate-y-full' : 'translate-y-0' }}" data-scroll-navbar="{{ $shouldHideNavbar ? 'true' : 'false' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full overflow-x-clip">
            <div class="flex justify-between items-center h-24 py-3">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/dashboard/logo.png') }}" alt="Pineus Tilu Logo" class="h-24 w-auto">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-1 flex-shrink">
                    <!-- Story -->
                    <a href="{{ route('cerita') }}" class="px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                        Story
                    </a>

                    <!-- Home -->
                    <a href="/" class="px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                        Home
                    </a>

                    <!-- Area Dropdown -->
                    <div class="relative dropdown-wrapper" data-dropdown="area">
                        <button class="dropdown-btn px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors flex items-center text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                            Area
                            <svg class="dropdown-icon w-4 h-4 ml-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="dropdown-menu opacity-0 invisible absolute left-1/2 -translate-x-1/2 mt-2 w-52 bg-white rounded-2xl shadow-xl transition-all duration-300 transform -translate-y-2 scale-95">
                            <div class="py-2">
                                <a href="/area/pineus-tilu-1" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                    <div class="text-base font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 1</div>
                                </a>
                                <a href="/area/pineus-tilu-2" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                    <div class="text-base font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 2</div>
                                </a>
                                <a href="/area/pineus-tilu-3-vip" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                    <div class="text-base font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 3 VIP</div>
                                </a>
                                <a href="/area/pineus-tilu-4" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                    <div class="text-base font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 4</div>
                                </a>
                                <div class="relative dropdown-submenu-wrapper">
                                    <button class="dropdown-submenu-btn w-full text-left px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer flex items-center justify-between rounded-xl">
                                        <span class="text-base font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu Cabin</span>
                                        <svg class="dropdown-submenu-icon w-4 h-4 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-submenu opacity-0 invisible absolute left-full -top-12 ml-2 w-48 bg-white rounded-2xl shadow-xl transition-all duration-300 transform -translate-y-2 scale-95">
                                        <div class="py-2">
                                            <a href="/cabin/vip" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                                <div class="text-base font-semibold" style="font-family: 'Poppins', sans-serif;">Cabin VIP</div>
                                            </a>
                                            <a href="/cabin/vvip" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                                <div class="text-base font-semibold" style="font-family: 'Poppins', sans-serif;">Cabin VVIP</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activities -->
                    <a href="{{ route('aktivitas') }}" class="px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                        Activities
                    </a>

                    <!-- Guidelines -->
                    <a href="{{ route('pedoman') }}" class="px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                        Guidelines
                    </a>

                    <!-- Morikafe -->
                    <a href="{{ route('morikafe') }}" class="px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                        Morikafe
                    </a>

                    <!-- FAQ -->
                    <a href="{{ route('faq') }}" class="px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                        FAQ
                    </a>

                    <!-- Reservation Dropdown -->
                    <div class="relative dropdown-wrapper" data-dropdown="reservasi">
                        <button class="dropdown-btn px-3 py-2 text-brand-primary hover:text-gray-700 transition-colors flex items-center text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                            Reservation
                            <svg class="dropdown-icon w-4 h-4 ml-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="dropdown-menu opacity-0 invisible absolute left-1/2 -translate-x-1/2 mt-2 w-52 bg-white rounded-2xl shadow-xl transition-all duration-300 transform -translate-y-2 scale-95">
                            <div class="py-2">
                                <a href="/reservasi/glamping" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                    <div class="flex items-center text-base font-semibold" style="font-family: 'Poppins', sans-serif;">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Glamping
                                    </div>
                                </a>
                                <a href="/reservasi/outbound" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 hover:text-gray-700 transition-colors cursor-pointer rounded-xl">
                                    <div class="flex items-center text-base font-semibold" style="font-family: 'Poppins', sans-serif;">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Outbound
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile/Login Button -->
                    @auth
                        <!-- Profile Dropdown -->
                        <div class="relative group ml-2">
                            <button class="flex items-center space-x-2 px-4 py-2 bg-brand-primary text-white rounded-full hover:bg-[#015a3a] transition-colors shadow-md hover:shadow-lg text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                                <div class="w-8 h-8 bg-white text-brand-primary rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ Auth::user()->initials() }}
                                </div>
                                <span>{{ Str::limit(Auth::user()->name, 15) }}</span>
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2">
                                <div class="py-2">
                                    {{-- Admin Panel Link - Only for admin/super-admin --}}
                                    @if(Auth::user()->hasRole(['super-admin', 'admin']))
                                        <a href="{{ url('/admin') }}" class="block px-4 py-3 text-amber-600 hover:bg-amber-50 transition-colors cursor-pointer border-b border-gray-100 rounded-xl">
                                            <div class="flex items-center text-base font-semibold" style="font-family: 'Poppins', sans-serif;">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                Admin Panel
                                            </div>
                                        </a>
                                    @endif
                                    <a href="{{ route('profile') }}" class="block px-4 py-3 text-brand-primary hover:bg-gray-100 transition-colors cursor-pointer rounded-xl">
                                        <div class="flex items-center text-base font-semibold" style="font-family: 'Poppins', sans-serif;">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Profile
                                        </div>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-3 text-brand-primary hover:bg-gray-100 transition-colors cursor-pointer rounded-xl">
                                            <div class="flex items-center text-base font-semibold" style="font-family: 'Poppins', sans-serif;">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Sign out
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Login Button -->
                        <a href="/login" class="ml-2 px-5 py-2 bg-brand-primary text-white rounded-full hover:bg-[#015a3a] transition-colors shadow-md hover:shadow-lg text-base cursor-pointer font-semibold" style="font-family: 'Poppins', sans-serif;">
                            Login
                        </a>
                    @endauth
                </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button id="mobile-menu-button" class="text-gray-700 hover:text-brand-primary focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                        <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden bg-white border-t overflow-hidden max-h-0 opacity-0 transition-all duration-300 ease-in-out transform origin-top scale-y-0">
            <div class="px-3 pt-2 pb-4 space-y-1 max-h-[calc(100vh-5rem)] overflow-y-auto">
                <a href="{{ route('cerita') }}" class="block px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                    Story
                </a>
                <a href="/" class="block px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                    Home
                </a>

                <!-- Mobile Area Dropdown -->
                <div class="relative mobile-dropdown-wrapper" data-mobile-dropdown="area">
                    <button class="mobile-dropdown-btn w-full flex justify-between items-center px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                        <span>Area</span>
                        <svg class="mobile-dropdown-icon w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mobile-dropdown-menu max-h-0 opacity-0 overflow-hidden transition-all duration-300 pl-3 mt-1 space-y-1">
                        <a href="/area/pineus-tilu-1" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 1</a>
                        <a href="/area/pineus-tilu-2" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 2</a>
                        <a href="/area/pineus-tilu-3-vip" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 3 VIP</a>
                        <a href="/area/pineus-tilu-4" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Pineus Tilu 4</a>
                        <div class="relative mobile-subdropdown-wrapper" data-mobile-subdropdown="cabin">
                            <button class="mobile-subdropdown-btn w-full flex justify-between items-center px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">
                                <span>Pineus Tilu Cabin</span>
                                <svg class="mobile-subdropdown-icon w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="mobile-subdropdown-menu hidden max-h-0 opacity-0 overflow-hidden transition-all duration-300 pl-3 mt-1 space-y-1">
                                <a href="/cabin/vip" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Cabin VIP</a>
                                <a href="/cabin/vvip" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Cabin VVIP</a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('aktivitas') }}" class="block px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                    Activities
                </a>
                <a href="{{ route('pedoman') }}" class="block px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                    Guidelines
                </a>
                <a href="{{ route('morikafe') }}" class="block px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                    Morikafe
                </a>
                <a href="{{ route('faq') }}" class="block px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                    FAQ
                </a>

                <!-- Mobile Reservation Dropdown -->
                <div class="relative mobile-dropdown-wrapper" data-mobile-dropdown="reservasi">
                    <button class="mobile-dropdown-btn w-full flex justify-between items-center px-3 py-2 text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                        <span>Reservation</span>
                        <svg class="mobile-dropdown-icon w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mobile-dropdown-menu max-h-0 opacity-0 overflow-hidden transition-all duration-300 pl-3 mt-1 space-y-1">
                        <a href="/reservasi/glamping" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Glamping</a>
                        <a href="/reservasi/outbound" class="block px-3 py-1.5 text-sm text-brand-primary hover:bg-gray-100 hover:text-gray-700 rounded-2xl font-semibold" style="font-family: 'Poppins', sans-serif;">Outbound</a>
                    </div>
                </div>

                <!-- Mobile Profile/Login -->
                @auth
                    <!-- Mobile Profile Section -->
                    <div class="border-t pt-3 mt-2">
                        <div class="px-3 py-2 flex items-center space-x-3 bg-gray-50 rounded-2xl mb-2">
                            <div class="w-10 h-10 bg-brand-primary text-white rounded-full flex items-center justify-center font-bold">
                                {{ Auth::user()->initials() }}
                            </div>
                            <div>
                                <div class="font-semibold text-brand-primary text-sm" style="font-family: 'Poppins', sans-serif;">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-xs text-gray-500" style="font-family: 'Poppins', sans-serif;">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        </div>
                        {{-- Admin Panel Link - Only for admin/super-admin (Mobile) --}}
                        @if(Auth::user()->hasRole(['super-admin', 'admin']))
                            <a href="{{ url('/admin') }}" class="block px-3 py-2 text-amber-600 hover:bg-amber-50 rounded-2xl transition-colors text-sm font-semibold mb-1" style="font-family: 'Poppins', sans-serif;">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Admin Panel
                                </div>
                            </a>
                        @endif
                        <a href="{{ route('profile') }}" class="block px-3 py-2 text-brand-primary hover:bg-gray-100 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profile
                            </div>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-2 text-red-600 hover:bg-red-50 rounded-2xl transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </div>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="/login" class="block mx-2 mt-3 px-6 py-2.5 bg-brand-primary text-white text-center rounded-full hover:bg-[#015a3a] transition-colors shadow-md text-sm font-semibold" style="font-family: 'Poppins', sans-serif;">
                        Login
                    </a>
                @endauth
            </div>
        </div>
</nav>
