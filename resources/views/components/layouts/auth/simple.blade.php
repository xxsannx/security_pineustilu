<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        {{-- Auth page specific styles --}}
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    </head>
    <body class="auth-background min-h-screen antialiased">
        <div class="auth-content min-h-svh w-full">
            {{ $slot }}
        </div>
        {{-- Auth page specific scripts --}}
        <script src="{{ asset('js/auth.js') }}" defer></script>
        @fluxScripts
    </body>
</html>
