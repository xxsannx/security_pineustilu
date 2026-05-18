@extends('layouts.app')

@section('title', 'Morikafe Pangalengan - Pineus Tilu - Glamping & Outbound')

@section('mainClass', 'pt-24 pb-0 min-h-screen')

@section('content')
@include('partials.morikafe.morikafe-hero-section')

@include('partials.morikafe.info-band')
<div class="max-w-6xl mx-auto px-6">
	@include('components.divider', [
		'baseClass' => 'border-t border-brand-primary/20 w-full',
		'class' => '!my-4',
		'ariaHidden' => 'true',
	])
</div>
@include('partials.morikafe.sarapan')
@include('partials.morikafe.menu-fasilitas')
@include('partials.morikafe.galeri-morikafe')
@include('partials.morikafe.ruangan-lesehan')

<x-reservation-cta :noMargin="true" />
@endsection

@push('scripts')
@vite('resources/js/pages/morikafe.js')
@endpush