@extends('layouts.app')

@section('title', 'Outdoor Activities - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 pb-0 min-h-screen')

@section('content')
<div class="w-full max-w-screen-xl mx-auto px-4 sm:px-6">
    <div class="text-center mt-4 mb-4 md:mb-8" data-aos="fade-up" data-aos-duration="600">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#017249] mb-2 md:mb-3" style="font-family: 'Bizon', sans-serif;" data-aos="fade-down" data-aos-duration="600" data-aos-delay="100">{{ $intro['title'] ?? 'OUTDOOR ACTIVITIES' }}</h2>
        <p class="text-gray-600 text-sm sm:text-base md:text-lg max-w-6xl mx-auto px-2" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            {{ $intro['description'] ?? 'Experience the thrill of rafting, flying fox, paintball, ATV & offroad, as well as team building activities that bring unforgettable moments of adventure and togetherness.' }}
        </p>
    </div>

    @include('partials.aktivitas.arung-jeram', ['activity' => $activities['arung-jeram'] ?? []])

    <!-- FLYING FOX + OFFROAD (side-by-side) -->
    <section class="w-full mb-6 md:mb-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                @include('partials.aktivitas.flying-fox', ['activity' => $activities['flying-fox'] ?? []])
                @include('partials.aktivitas.offroad', ['activity' => $activities['offroad'] ?? []])
            </div>
        </div>
    </section>

    <!-- FUN ATV + PAINTBALL (side-by-side) -->
    <section class="w-full mb-6 md:mb-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                @include('partials.aktivitas.fun-atv', ['activity' => $activities['fun-atv'] ?? []])
                @include('partials.aktivitas.paintball', ['activity' => $activities['paintball'] ?? []])
            </div>
        </div>
    </section>

    <!-- TEAM BUILDING (full width) -->
    <section class="w-full mb-6 md:mb-8">
        <div class="max-w-6xl mx-auto">
            @include('partials.aktivitas.team-building', ['activity' => $activities['team-building'] ?? []])
        </div>
    </section>

    @include('partials.aktivitas.information', ['information' => $information ?? []])
    @include('partials.aktivitas.activities-grid', ['aroundCards' => $aroundCards ?? []])

</div>

<x-reservation-cta />

@push('scripts')
@vite('resources/js/pages/aktivitas.js')
@endpush
@endsection