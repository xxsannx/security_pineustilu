@extends('layouts.app')

@section('title', 'Frequently Asked Questions - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 pb-0 min-h-screen')

@section('content')
    <div class="w-full max-w-screen-xl mx-auto px-4 sm:px-6">
        <div class="text-center mt-2 sm:mt-4 mb-4 sm:mb-6 md:mb-8" data-aos="fade-up" data-aos-duration="600">
            <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-[#017249] mb-2 sm:mb-3" style="font-family: 'Bizon', sans-serif;" data-aos="fade-down" data-aos-duration="600" data-aos-delay="100">FREQUENTLY ASKED QUESTIONS</h2>
            <p class="text-gray-600 text-xs sm:text-sm md:text-base lg:text-lg max-w-6xl mx-auto" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                Find quick answers to common questions about Pineus Tilu, facilities, booking, and activities.
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            @include('partials.faq.search-bar')
            @include('partials.faq.faq-items')
        </div>
    </div>

    <x-reservation-cta />
    <x-reschedule-cancellation-cta/>

    @push('scripts')
        @vite('resources/js/pages/faq.js')
    @endpush
@endsection
