@extends('layouts.app')

@section('title', 'Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24')

@section('content')
    {{-- Hero Section --}}
    @include('partials.dashboard.hero-section')

    {{-- About Section --}}
    @include('partials.dashboard.about-section')

    {{-- Reservation CTA Section --}}
    <x-reservation-cta />

    {{-- Area Cards Section --}}
    @include('partials.dashboard.area-section')

    {{-- Map & Location Section --}}
    @include('partials.dashboard.map-location-section')

    {{-- Aksesibilitas Section --}}
    @include('partials.dashboard.accessibility-section')

    {{-- Reservation CTA Section --}}
    <x-reservation-cta />

    {{-- Accessibility Modals --}}
    @include('partials.modals.modal-jabodetabek')
    @include('partials.modals.modal-luar-jawa')
    @include('partials.modals.modal-jawa-tengah-timur')
    @include('partials.modals.modal-luar-negeri')
@endsection

@push('scripts')
    @vite('resources/js/pages/dashboard.js')
@endpush
