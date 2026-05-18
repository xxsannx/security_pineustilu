@extends('layouts.app')

@section('title', 'Cancellation Successful - Pineus Tilu')

@section('mainClass', 'pt-24 bg-[#f6fbf8] min-h-screen')
@section('content')
    <div class="w-full max-w-screen-xl mx-auto px-6">
        <div class="min-h-[calc(100svh-6rem-12rem)] py-8">
            <div class="w-full max-w-2xl mx-auto text-center">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-[#017249]">Pembatalan Berhasil</h2>

                    @if(session('success'))
                        <p class="mt-4 text-gray-700">{{ session('success') }}</p>
                    @else
                        <p class="mt-4 text-gray-700">Pembatalan dan pengembalian dana telah diproses. Silakan cek email Anda untuk detail lebih lanjut.</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="px-6 py-3 bg-[#146B4A] text-white rounded-xl">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
