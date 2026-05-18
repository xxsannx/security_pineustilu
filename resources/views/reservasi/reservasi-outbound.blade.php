@extends('layouts.app')

@section('title', 'Outbound Reservation - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 w-full max-w-screen-xl mx-auto px-6 pb-16')

@section('content')
    <x-page-heading title="OUTBOUND RESERVATION" wrapperClass="text-center py-8" />

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Form Container - 2 Column Layout (same as Glamping) -->
    <form method="POST" action="{{ route('reservasi.outbound.store') }}" id="outReservasiForm" autocomplete="off">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- LEFT COLUMN: Detail Reservasi (spans 2 columns on large screens) -->
            <div class="lg:col-span-2">
                @include('partials.reservasi-outbound.detail')
            </div>

            <!-- RIGHT COLUMN: Preview Detail Pesanan (sticky on desktop) -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-28">
                    @include('partials.reservasi-outbound.preview-detail')
                </div>
            </div>
        </div>
    </form>

    @include('partials.reservasi-outbound.modal-info')

    @push('scripts')
        @vite(['resources/js/pages/reservasi-outbound.js'])
    @endpush
@endsection
