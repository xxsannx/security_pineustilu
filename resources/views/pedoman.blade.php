@extends('layouts.app')

@section('title', 'Guidelines - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'min-h-screen pt-24 pb-0')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-4 sm:space-y-6 md:space-y-8">
            @include('partials.pedoman.syarat-ketentuan')

            @include('partials.pedoman.kebijakan-informasi')
        </div>
    </div>

    <x-reschedule-cancellation-cta />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-4 sm:space-y-6 md:space-y-8">
            @include('partials.pedoman.parkir')

            @include('partials.pedoman.asuransi')
        </div>
    </div>

    <x-reservation-cta />
@endsection

@push('scripts')
    @vite('resources/js/pages/pedoman.js')
@endpush
