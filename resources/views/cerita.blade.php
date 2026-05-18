@extends('layouts.app')

@section('title', 'Story - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 pb-0 w-full')

@section('content')

    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6">
        <div class="relative z-10 w-full">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 overflow-hidden">
                <div class="text-center mt-2 sm:mt-4" data-aos="fade-up" data-aos-duration="600">
                    <h1 id="storyHeader"
                        class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-[#017249] mb-4 sm:mb-6 md:mb-8 leading-tight"
                        style="font-family: 'Bizon', sans-serif;"
                        data-aos="fade-down" data-aos-duration="600" data-aos-delay="100">
                        STORY
                    </h1>
                </div>
            </div>
        </div>

        {{-- Content Section --}}
        <section class="w-full" role="main">
            {{-- Language Switcher --}}
            <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                @include('partials.cerita.language-switcher')
            </div>

            {{-- Story Content + Credit (no card; blends with background) --}}
            <div id="ceritaGrid" aria-live="polite" class="space-y-6 sm:space-y-8 md:space-y-10">
                @include('partials.cerita.content-english')
                @include('partials.cerita.content-japanese')

                @include('components.divider', ['class' => '!my-0', 'ariaHidden' => 'true'])
                @include('partials.cerita.credit')
            </div>
        </section>

    </div>

    <x-reservation-cta />

@endsection

@push('scripts')
    @vite('resources/js/pages/cerita.js')
@endpush
