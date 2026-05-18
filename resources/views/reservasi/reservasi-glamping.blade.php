@extends('layouts.app')

@section('title', 'Glamping Reservation - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 w-full max-w-screen-xl mx-auto px-6 pb-16')

@push('preload')
{{-- Preload critical JS for this page --}}
<link rel="preload" href="{{ asset('js/flatpickr.js') }}" as="script">
@endpush

@section('content')
        <x-page-heading title="GLAMPING RESERVATION" wrapperClass="text-center py-8" />

        <!-- Form Container - 2 Column Layout -->
        <form method="POST" action="{{ route('reservasi.glamping.store') }}" id="reservasiForm" autocomplete="off">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- LEFT COLUMN: Detail Reservasi (spans 2 columns on large screens) -->
                <div class="lg:col-span-2">
                    @include('partials.reservasi-glamping.detail')
                </div>

                <!-- RIGHT COLUMN: Preview Detail Pesanan (sticky on desktop) -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-28">
                        @include('partials.reservasi-glamping.preview-detail')
                    </div>
                </div>
            </div>
        </form>

        @include('partials.modals.modal-info')
        @include('partials.modals.modal-amenities')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/flatpickr.js') }}" defer></script>
@vite(['resources/js/pages/reservasi-glamping.js'])
@endpush
@endsection
