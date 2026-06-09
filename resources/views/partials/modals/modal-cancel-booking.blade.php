<!-- Cancel Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 backdrop-blur-sm bg-black/20 z-[9999] items-center justify-center p-4 hidden"
    role="dialog" aria-modal="true" aria-labelledby="cancelModalTitle">
    <div
        class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border-2 border-gray-100 transform transition-all">
        <!-- Modal Header -->
        <div class="bg-gradient-to-br from-gray-50 to-white border-b-2 border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-2">
                @if(in_array($status ?? 'booking', ['booking', 'pembayaran']))
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-2.5 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 id="cancelModalTitle" class="text-xl font-bold text-gray-800"
                        style="font-family: 'Bizon', sans-serif;">
                        Batalkan Pesanan?
                    </h3>
                @else
                    <div class="bg-gradient-to-br from-[#017249] to-[#0b5a3e] p-2.5 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <h3 id="cancelModalTitle" class="text-xl font-bold text-gray-800"
                        style="font-family: 'Bizon', sans-serif;">
                        Kembali ke Menu Utama?
                    </h3>
                @endif
            </div>

            @if(in_array($status ?? 'booking', ['booking', 'pembayaran']))
                <p class="text-gray-600 text-sm ml-12">Apakah Anda yakin ingin kembali ke halaman reservasi?</p>
            @else
                <p class="text-gray-600 text-sm ml-12">Apakah Anda yakin ingin kembali ke halaman utama?</p>
            @endif
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            @if(in_array($status ?? 'booking', ['booking', 'pembayaran']))
                <div class="bg-amber-50 border-2 border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-amber-800">
                            Data pesanan Anda saat ini belum tersimpan. Jika Anda kembali, data akan hilang.
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-[#f8fffe] border-2 border-[#017249]/20 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#017249] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-[#017249]">
                            Pesanan Anda sudah tersimpan. Anda dapat melihat detail pesanan ini kapan saja melalui menu
                            profil Anda.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" id="cancelModalClose"
                    class="flex-1 px-5 py-3 rounded-xl border-2 border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200 text-center">
                    Tetap Disini
                </button>
                @if(in_array($status ?? 'booking', ['booking', 'pembayaran']))
                    <button type="button" id="confirmCancelBooking"
                        class="flex-1 px-5 py-3 rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold shadow-lg hover:shadow-xl transition-all duration-300 text-center">
                        Ya, Kembali
                    </button>
                @else
                    <button type="button" id="confirmReturnMainMenu"
                        class="flex-1 px-5 py-3 rounded-xl bg-gradient-to-r from-[#017249] to-[#0b5a3e] hover:from-[#0b5a3e] hover:to-[#017249] text-white font-bold shadow-lg hover:shadow-xl transition-all duration-300 text-center">
                        Ya, Kembali
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>