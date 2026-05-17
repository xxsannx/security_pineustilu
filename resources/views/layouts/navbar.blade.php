<!-- Navbar -->
<nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Pineus Tilu Logo" class="h-16 w-auto">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-1">
                    <!-- Cerita -->
                    <a href="{{ route('cerita') }}" class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors text-lg" style="font-family: 'Veteran', monospace;">
                        Cerita
                    </a>

                    <!-- Beranda -->
                    <a href="/" class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors text-lg" style="font-family: 'Veteran', monospace;">
                        Beranda
                    </a>

                    <!-- Area Dropdown -->
                    <div class="relative group">
                        <button class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors flex items-center text-lg" style="font-family: 'Veteran', monospace;">
                            Area
                            <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2">
                            <div class="py-2">
                                <a href="/area/pineus-tilu-1" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <div class="font-semibold text-base" style="font-family: 'Veteran', monospace;">Pineus Tilu 1</div>
                                </a>
                                <a href="/area/pineus-tilu-2" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <div class="font-semibold text-base" style="font-family: 'Veteran', monospace;">Pineus Tilu 2</div>
                                </a>
                                <a href="/area/pineus-tilu-3-vip" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <div class="font-semibold text-base" style="font-family: 'Veteran', monospace;">Pineus Tilu 3 VIP</div>
                                </a>
                                <a href="/area/pineus-tilu-4" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <div class="font-semibold text-base" style="font-family: 'Veteran', monospace;">Pineus Tilu 4</div>
                                </a>
                                <a href="/area/pineus-tilu-cabin" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <div class="font-semibold text-base" style="font-family: 'Veteran', monospace;">Pineus Tilu Cabin</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas -->
                    <a href="{{ route('aktivitas') }}" class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors text-lg" style="font-family: 'Veteran', monospace;">
                        Aktivitas
                    </a>

                    <!-- Pedoman -->
                    <a href="{{ route('pedoman') }}" class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors text-lg" style="font-family: 'Veteran', monospace;">
                        Pedoman
                    </a>

                    <!-- Morikafe -->
                    <a href="{{ route('morikafe') }}" class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors text-lg" style="font-family: 'Veteran', monospace;">
                        Morikafe
                    </a>

                    <!-- FAQ -->
                    <a href="{{ route('faq') }}" class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors text-lg" style="font-family: 'Veteran', monospace;">
                        FAQ
                    </a>

                    <!-- Reservasi Dropdown -->
                    <div class="relative group">
                        <button class="px-4 py-2 text-[#017249] hover:text-gray-700 font-semibold transition-colors flex items-center text-lg" style="font-family: 'Veteran', monospace;">
                            Reservasi
                            <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2">
                            <div class="py-2">
                                <a href="/reservasi/glamping" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <div class="font-semibold flex items-center text-base" style="font-family: 'Veteran', monospace;">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Glamping
                                    </div>
                                </a>
                                <a href="/reservasi/outbond" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                    <div class="font-semibold flex items-center text-base" style="font-family: 'Veteran', monospace;">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Outbond
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <a href="/login" class="ml-4 px-6 py-2 bg-[#017249] text-white rounded-full hover:bg-[#015a3a] font-semibold transition-colors shadow-md hover:shadow-lg text-lg" style="font-family: 'Veteran', monospace;">
                        Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-[#017249] focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('cerita') }}" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                    Cerita
                </a>
                <a href="/" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                    Beranda
                </a>

                <!-- Mobile Area Dropdown -->
                <div class="relative">
                    <button id="mobile-area-button" class="w-full flex justify-between items-center px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                        <span>Area</span>
                        <svg class="w-4 h-4 transition-transform" id="mobile-area-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobile-area-menu" class="hidden pl-4 mt-1 space-y-1">
                        <a href="/area/pineus-tilu-1" class="block px-4 py-2 text-base text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg font-semibold" style="font-family: 'Veteran', monospace;">Pineus Tilu 1</a>
                        <a href="/area/pineus-tilu-2" class="block px-4 py-2 text-base text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg font-semibold" style="font-family: 'Veteran', monospace;">Pineus Tilu 2</a>
                        <a href="/area/pineus-tilu-3-vip" class="block px-4 py-2 text-base text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg font-semibold" style="font-family: 'Veteran', monospace;">Pineus Tilu 3 VIP</a>
                        <a href="/area/pineus-tilu-4" class="block px-4 py-2 text-base text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg font-semibold" style="font-family: 'Veteran', monospace;">Pineus Tilu 4</a>
                        <a href="/area/pineus-tilu-cabin" class="block px-4 py-2 text-base text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg font-semibold" style="font-family: 'Veteran', monospace;">Pineus Tilu Cabin</a>
                    </div>
                </div>

                <a href="{{ route('aktivitas') }}" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                    Aktivitas
                </a>
                <a href="{{ route('pedoman') }}" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                    Pedoman
                </a>
                <a href="{{ route('morikafe') }}" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                    Morikafe
                </a>
                <a href="{{ route('faq') }}" class="block px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                    FAQ
                </a>

                <!-- Mobile Reservasi Dropdown -->
                <div class="relative">
                    <button id="mobile-reservasi-button" class="w-full flex justify-between items-center px-4 py-3 text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-colors text-base font-semibold" style="font-family: 'Veteran', monospace;">
                        <span>Reservasi</span>
                        <svg class="w-4 h-4 transition-transform" id="mobile-reservasi-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobile-reservasi-menu" class="hidden pl-4 mt-1 space-y-1">
                        <a href="/reservasi/glamping" class="block px-4 py-2 text-base text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg font-semibold" style="font-family: 'Veteran', monospace;">Glamping</a>
                        <a href="/reservasi/outbond" class="block px-4 py-2 text-base text-[#017249] hover:bg-gray-100 hover:text-gray-700 rounded-lg font-semibold" style="font-family: 'Veteran', monospace;">Outbond</a>
                    </div>
                </div>

                <a href="/login" class="block mx-4 mt-4 px-6 py-3 bg-[#017249] text-white text-center rounded-full hover:bg-[#015a3a] font-semibold transition-colors shadow-md text-base" style="font-family: 'Veteran', monospace;">
                    Login
                </a>
            </div>
        </div>
    </nav>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });

    // Mobile Area dropdown
    const mobileAreaButton = document.getElementById('mobile-area-button');
    const mobileAreaMenu = document.getElementById('mobile-area-menu');
    const mobileAreaIcon = document.getElementById('mobile-area-icon');

    mobileAreaButton.addEventListener('click', () => {
        mobileAreaMenu.classList.toggle('hidden');
        mobileAreaIcon.classList.toggle('rotate-180');
    });

    // Mobile Reservasi dropdown
    const mobileReservasiButton = document.getElementById('mobile-reservasi-button');
    const mobileReservasiMenu = document.getElementById('mobile-reservasi-menu');
    const mobileReservasiIcon = document.getElementById('mobile-reservasi-icon');

    mobileReservasiButton.addEventListener('click', () => {
        mobileReservasiMenu.classList.toggle('hidden');
        mobileReservasiIcon.classList.toggle('rotate-180');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });
</script>