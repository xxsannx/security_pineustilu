@extends('layouts.app')

@section('title', ucfirst($currentTab ?? 'Profile') . ' - Pineus Tilu')

@section('mainClass', 'pt-24')

@section('content')
    <section class="relative px-3 sm:px-4 py-6 sm:py-8 md:py-10 lg:py-12" data-aos="fade-up" data-aos-duration="600">
        <div class="max-w-7xl mx-auto relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
                {{-- Sidebar Component --}}
                @include('partials.profile.sidebar', ['currentTab' => $currentTab ?? 'profile'])

                {{-- Main Content --}}
                <div class="lg:col-span-3 space-y-4 sm:space-y-6 md:space-y-8">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-[#017249] p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-md" data-aos="fade-down">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#017249] mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-[#017249] font-semibold font-poppins text-sm sm:text-base">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Dynamic Content Based on Current Tab --}}
                    @switch($currentTab ?? 'profile')
                        @case('profile')
                            @include('partials.profile.profileinfo')
                            @break
                        
                        @case('bookings')
                            @include('partials.profile.mybookings', ['bookings' => $bookings ?? collect()])
                            @break
                        
                        @case('reschedule')
                            @include('partials.profile.myreschedule', ['reschedules' => $reschedules ?? collect()])
                            @break
                        
                        @case('cancellation')
                            @include('partials.profile.mycancellation', ['cancellations' => $cancellations ?? collect()])
                            @break
                        
                        @default
                            @include('partials.profile.profileinfo')
                    @endswitch
                </div>
            </div>
        </div>
    </section>

    {{-- Modals --}}
    @include('partials.modals.modalprofile')
    @include('partials.modals.modal-order-details')
@endsection

@push('scripts')
    @vite('resources/js/pages/profile.js')
    @vite('resources/js/pages/order-details-modal.js')
@endpush
