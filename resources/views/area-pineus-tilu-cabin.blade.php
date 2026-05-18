@extends('layouts.app')

@section('mainClass', 'pt-24')

@php
    $areaName = $area->name ?? 'Pineus Tilu Cabin VVIP';
    $areaSlug = $area->slug ?? 'pineus-tilu-cabin-vvip';
    $cabinTier = str_contains($areaSlug, 'vvip') ? 'VVIP' : 'VIP';
    $cabinPath = $cabinTier === 'VVIP' ? 'pt-cabin-vvip' : 'pt-cabin';
    $heroImages = $cabinTier === 'VVIP' ? [
        ['path' => 'cabin-vvip', 'file' => 'main.jpeg'],
        ['path' => 'cabin-vvip', 'file' => 'main2.jpeg'],
        ['path' => 'cabin-vvip', 'file' => 'main3.jpeg'],
        ['path' => 'cabin-vvip', 'file' => 'main4.jpeg'],
        ['path' => 'cabin-vvip', 'file' => 'main5.jpeg'],
    ] : [
        ['path' => 'cabin-vip', 'file' => 'main.jpg'],
        ['path' => 'cabin-vip', 'file' => 'main2.jpeg'],
        ['path' => 'cabin-vip', 'file' => 'main3.jpg'],
        ['path' => 'cabin-vip', 'file' => 'main4.jpg'],
    ];
@endphp

@section('title', $areaName . ' - Exclusive Forest Sanctuary - Pineus Tilu')

@section('content')
    <!-- Hero Section -->
    <section class="relative -mt-24 bg-gray-800 min-h-screen overflow-hidden">
        <!-- Hero Carousel -->
        <div class="hero-carousel absolute inset-0 w-full h-full overflow-hidden" data-autoplay="3000">
            <div class="hero-carousel-track flex w-full h-full transition-transform duration-700 ease-in-out">
                @foreach($heroImages as $img)
                <div class="hero-carousel-slide w-full h-full flex-shrink-0">
                    <img src="{{ asset('images/main/' . $img['path'] . '/' . $img['file']) }}" alt="{{ $areaName }}"
                        class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
        </div>
        <div class="absolute inset-0 bg-black/35"></div>

        <!-- Hero Carousel Indicators -->
        <div class="hero-carousel-indicators absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
            @foreach($heroImages as $index => $img)
            <span class="hero-indicator-dot h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-6' : 'bg-white/50 w-2' }}"></span>
            @endforeach
        </div>

        <!-- Blok teks diposisikan absolut di tengah-atas -->
        <div class="absolute mt-4 w-full text-white text-center"
            style="top:clamp(96px,10vh,140px);padding:0 clamp(16px,6vw,120px);">
            <h1 class="text-3xl md:text-4xl font-bold text-white leading-none" data-aos="fade-down" data-aos-duration="800"
                style="font-family: 'Bizon', sans-serif;">
                <span class="block">PINEUS TILU</span>
                <span class="block text-[#D4AF6A] font-semibold"
                    style="font-family:'Bizon',ui-sans-serif,system-ui;">CABIN {{ $cabinTier }}</span>
            </h1>
            <p class="mt-4 text-lg" data-aos="fade-down" data-aos-duration="800"
                style="font-family:'Poppins',ui-sans-serif,system-ui;text-shadow:1px 1px 3px rgba(0,0,0,0.5);">
                The Private Forest Sanctuary
            </p>

            <!-- Quick Features -->
            <div class="flex flex-wrap justify-center gap-4 sm:gap-6 mt-4" data-aos="fade-up" data-aos-duration="800"
                data-aos-delay="300">
                <div class="flex items-center gap-2 text-sm sm:text-base">
                    <svg class="w-5 h-5 text-[#D4AF6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">{{ $unit->default_people ?? 4 }}-{{ $unit->max_people ?? 8 }} Guests</span>
                </div>
                <div class="flex items-center gap-2 text-sm sm:text-base">
                    <svg class="w-5 h-5 text-[#D4AF6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">100m² Cabin</span>
                </div>
                <div class="flex items-center gap-2 text-sm sm:text-base">
                    <svg class="w-5 h-5 text-[#D4AF6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span class="font-medium">Premium Service</span>
                </div>
            </div>
        </div>
    </section>

    @include('partials.area-cabin.skema-deck', ['cabinTier' => $cabinTier, 'cabinPath' => $cabinPath])
    @include('partials.area-cabin.harga-kapasitas', ['cabinTier' => $cabinTier, 'cabinPath' => $cabinPath])
    @include('partials.promo-ramadhan', ['cabinTier' => $cabinTier, 'cabinPath' => $cabinPath])
    @include('partials.area-cabin.premium-amenities', [
        'cabinTier' => $cabinTier,
        'cabinPath' => $cabinPath,
    ])
    @include('components.barang-tambahan-cta-cabin')
    @include('partials.area-cabin.galeri', ['cabinTier' => $cabinTier, 'cabinPath' => $cabinPath])
    @include('components.reservation-cta')
@endsection

@push('scripts')
    @vite('resources/js/pages/area.js')
@endpush
