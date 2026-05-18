<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pineus Tilu - Glamping & Camping')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}?v={{ time() }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}?v={{ time() }}" type="image/svg+xml">
    
    {{-- Preconnect to external resources --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    {{-- DNS prefetch for common CDNs --}}
    <link rel="dns-prefetch" href="//www.google.com">
    <link rel="dns-prefetch" href="//maps.googleapis.com">
    
    {{-- Preload critical fonts --}}
    @stack('preload')
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/aos.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('css/aos.css') }}"></noscript>
    @stack('styles')
</head>
<body class="font-sans antialiased">
    @include('layouts.navbar')

    <main class="@yield('mainClass', 'pt-24')">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Floating WhatsApp Button -->
    <x-floating-whatsapp />

    <script src="{{ asset('js/aos.js') }}" defer></script>
    @vite(['resources/js/pages/navbar-menu.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    easing: 'ease-in-out',
                    once: true,
                    offset: 0,
                    throttleDelay: 99,
                    debounceDelay: 50,
                    anchorPlacement: 'top-bottom',
                    disable: false
                });
                
                // Trigger AOS refresh after a short delay to animate visible elements
                setTimeout(function() {
                    AOS.refresh();
                }, 100);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
