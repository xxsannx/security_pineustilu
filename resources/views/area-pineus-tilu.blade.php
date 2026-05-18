@extends('layouts.app')

@section('mainClass', 'pt-24')

@php
    $areaName = $area->name ?? 'Pineus Tilu 1';
    $areaSlug = $area->slug ?? 'pineus-tilu-1';
    $heroPath = match($areaSlug) {
        'pineus-tilu-1' => 'pt1',
        'pineus-tilu-2' => 'pt2',
        'pineus-tilu-3-vip' => 'pt3-vip',
        'pineus-tilu-4' => 'pt4',
        default => 'pt1'
    };
    $heroImages = match($areaSlug) {
        'pineus-tilu-1' => [
            ['path' => 'pt1', 'file' => 'main.jpg'],
            ['path' => 'pt1', 'file' => 'main2.jpg'],
            ['path' => 'pt1', 'file' => 'main3.jpg'],
        ],
        'pineus-tilu-2' => [
            ['path' => 'pt2', 'file' => 'main.jpg'],
            ['path' => 'pt2', 'file' => 'main2.jpg'],
            ['path' => 'pt2', 'file' => 'main3.jpg'],
            ['path' => 'pt2', 'file' => 'main4.jpg'],
        ],
        'pineus-tilu-3-vip' => [
            ['path' => 'pt3-vip', 'file' => 'main.jpg'],
            ['path' => 'pt3-vip', 'file' => 'main2.JPG'],
            ['path' => 'pt3-vip', 'file' => 'main3.png'],
            ['path' => 'pt3-vip', 'file' => 'main4.JPG'],
            ['path' => 'pt3-vip', 'file' => 'main5.JPG'],
        ],
        'pineus-tilu-4' => [
            ['path' => 'pt4', 'file' => 'main.jpg'],
            ['path' => 'pt4', 'file' => 'main2.jpg'],
            ['path' => 'pt4', 'file' => 'main3.jpg'],
        ],
        default => [
            ['path' => 'pt1', 'file' => 'main.jpg'],
            ['path' => 'pt1', 'file' => 'main2.jpg'],
            ['path' => 'pt1', 'file' => 'main3.jpg'],
        ]
    };
    $number = preg_replace('/[^0-9]/', '', $areaName) ?: 1;
@endphp

@section('title', $areaName . ' - Pineus Tilu - Glamping & Outbound')

@section('content')
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
        <div class="absolute w-full mt-4 text-white text-center" style="top:clamp(96px,10vh,140px);padding:0 clamp(16px,6vw,120px);pointer-events:none;">
            <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight" data-aos="fade-down" data-aos-duration="600"
                style="font-family: 'Bizon', sans-serif;">
                <span>PINEUS TILU</span>
                <span class="font-semibold" style="font-family:'Poppins',ui-sans-serif,system-ui;">{{ $number }}</span>
            </h1>
            <p class="italic mt-1" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100"
                style="font-family:'Poppins',ui-sans-serif,system-ui;font-style:italic;font-size:clamp(14px,2.2vw,22px);text-shadow:1px 1px 3px rgba(0,0,0,0.5);">
                Restore Your Life
            </p>
        </div>
    </section>

       
        @include('partials.area.skema-deck')
        @include('partials.area.harga-kapasitas')
        @include('partials.promo-ramadhan')
        @include('partials.area.fasilitas')
        @include('components.barang-tambahan-cta')
        @include('partials.area.galeri')
        @include('components.reservation-cta')

@endsection

@push('scripts')
    @vite('resources/js/pages/area.js')
@endpush