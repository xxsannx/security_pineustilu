<!-- Preview Detail Pesanan (Sticky Sidebar) -->
<section class="bg-white rounded-xl shadow-md p-5 border-2 border-[#017249]/10">
    <h3 class="text-lg font-bold text-[#017249] mb-5 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Preview Detail Pesanan
    </h3>

    <!-- Info Preview Cards -->
    <div class="space-y-3 mb-5">
        <!-- Activity Date -->
        <div class="bg-gradient-to-r from-[#017249]/5 to-transparent rounded-xl p-3">
            <div class="text-[10px] text-gray-500 mb-1">Tanggal Aktivitas</div>
            <div class="font-semibold text-gray-800 text-sm" id="previewDate">- - -</div>
        </div>

        <!-- Selected Activity -->
        <div class="bg-gradient-to-r from-[#017249]/5 to-transparent rounded-xl p-3">
            <div class="text-[10px] text-gray-500 mb-1">Aktivitas Outbound</div>
            <div class="font-semibold text-gray-800 text-sm" id="previewActivity">{{ $outbounds->first()->name ?? '-' }}</div>
        </div>

        <!-- Selected Variant (if any) -->
        <div id="previewVariantSection" class="bg-gradient-to-r from-[#017249]/5 to-transparent rounded-xl p-3 hidden">
            <div class="text-[10px] text-gray-500 mb-1">Variant</div>
            <div class="font-semibold text-gray-800 text-sm" id="previewVariant">-</div>
        </div>

        <!-- Number of Participants -->
        <div class="bg-gradient-to-r from-[#017249]/5 to-transparent rounded-xl p-3">
            <div class="text-[10px] text-gray-500 mb-1">Jumlah Peserta & Nama</div>
            <div class="font-semibold text-gray-800 text-sm" id="previewGuest">{{ $outbounds->first()->min_participants ?? 1 }} orang (-)</div>
        </div>
    </div>

    <!-- Extra Add-ons Preview -->
    <div class="mb-5">
        <div class="text-xs font-medium text-gray-700 mb-2">Extra Add-ons:</div>
        <div id="previewExtras" class="flex flex-wrap gap-1.5">
            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg">Belum ada add-ons dipilih</span>
        </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-gray-200 my-4"></div>

    <!-- Price Summary -->
    <div class="bg-gradient-to-br from-[#017249]/10 to-[#0b5a3e]/5 rounded-xl p-4 border border-[#017249]/20 mb-5">
        <div class="text-xs text-gray-600 mb-1">Estimasi Total Harga</div>
        <div class="text-2xl font-extrabold text-[#017249] mb-3" id="previewPrice">Rp 0</div>
        <div class="text-[10px] text-gray-500 mb-3">*Harga final akan dihitung setelah konfirmasi</div>

        <div class="space-y-1.5 text-xs">
            <div class="flex justify-between text-gray-600">
                <span>Harga Aktivitas:</span>
                <span id="previewActivityPrice" class="font-semibold">Rp 0</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Qty:</span>
                <span id="previewQty" class="font-semibold">x1</span>
            </div>
            <div id="previewExtrasRow" class="flex justify-between text-gray-600 hidden">
                <span>Add-ons:</span>
                <span id="previewExtrasPrice" class="font-semibold">Rp 0</span>
            </div>
        </div>
    </div>

    <!-- Hidden Inputs -->
    <input type="hidden" name="outbound_id" id="selectedOutboundId" value="{{ $outbounds->first()->id ?? '' }}">
    <input type="hidden" name="variant_id" id="selectedVariantId">
    <input type="hidden" name="activity_date" id="hiddenActivityDate">

    <!-- Contact Information -->
    @php
        $prefill = $contactPrefill ?? [];
        $isAuth = (bool) ($prefill['is_authenticated'] ?? false);
        $isGoogle = (bool) ($prefill['is_google'] ?? false);
        $countryCodeDefault = $prefill['country_code'] ?? '+62';
        $phoneDisplayPrefill = (string) ($prefill['phone'] ?? '');
        $phoneDigits = preg_replace('/\D+/', '', $phoneDisplayPrefill);
        $codeDigits = preg_replace('/\D+/', '', (string) $countryCodeDefault);
        if ($phoneDisplayPrefill !== '' && $codeDigits === '62') {
            if (str_starts_with($phoneDisplayPrefill, '+') && str_starts_with($phoneDigits, $codeDigits)) {
                $local = substr($phoneDigits, strlen($codeDigits));
                if ($local !== '') $phoneDisplayPrefill = '0' . ltrim($local, '0');
            } elseif (str_starts_with($phoneDigits, $codeDigits)) {
                $local = substr($phoneDigits, strlen($codeDigits));
                if ($local !== '') $phoneDisplayPrefill = '0' . ltrim($local, '0');
            } elseif (!str_starts_with($phoneDigits, '0') && str_starts_with($phoneDigits, '8')) {
                $phoneDisplayPrefill = '0' . $phoneDigits;
            }
        }
    @endphp
    <div class="space-y-3 mb-5">
        <div>
            <label class="block text-xs text-gray-500 mb-1" for="outName">Nama Lengkap</label>
            <input id="outName" name="name" type="text"
                class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400 text-sm {{ $isAuth ? 'bg-gray-50' : '' }}"
                placeholder="Masukkan nama lengkap" value="{{ old('name', $isAuth ? ($prefill['name'] ?? '') : '') }}" {{ $isAuth ? 'readonly' : '' }} required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1" for="outEmail">Email</label>
            <input id="outEmail" name="email" type="email"
                class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400 text-sm {{ $isAuth ? 'bg-gray-50' : '' }}"
                placeholder="email@domain.com" value="{{ old('email', $isAuth ? ($prefill['email'] ?? '') : '') }}" {{ $isAuth ? 'readonly' : '' }} required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1" for="outPhone">Nomor Telepon</label>
            <input id="outPhone" name="phone" type="tel"
                class="w-full rounded-xl border-2 border-gray-200 px-3 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 placeholder-gray-400 text-sm {{ ($isAuth && !$isGoogle) ? 'bg-gray-50' : '' }}"
                placeholder="08xxxxxxxx" value="{{ old('phone', $phoneDisplayPrefill) }}" {{ ($isAuth && !$isGoogle) ? 'readonly' : '' }} required>
        </div>
    </div>

    <!-- Terms & Submit -->
    <div>
        <div class="flex items-start gap-2 mb-2">
            <input type="checkbox" id="outAgree" name="agree" required
                class="h-4 w-4 mt-0.5 text-[#017249] border-gray-300 rounded focus:ring-[#017249] cursor-pointer"
                @error('agree') aria-describedby="outAgreeError" @enderror>
            <label for="outAgree" class="text-xs text-gray-600 cursor-pointer">
                Saya setuju dengan
                <a href="{{ route('pedoman') }}#syarat-heading" class="font-semibold text-[#017249] underline underline-offset-2 hover:text-[#0b5a3e]">Terms &amp; Conditions</a>
            </label>
        </div>
        @error('agree')
            <p id="outAgreeError" class="text-xs text-red-600 font-medium mb-3">{{ $message }}</p>
        @enderror
        <button type="submit"
            class="w-full bg-gradient-to-r from-[#017249] to-[#0b5a3e] hover:from-[#0b5a3e] hover:to-[#017249] text-white rounded-xl py-3 font-bold text-sm shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Pesan Sekarang
        </button>
    </div>
</section>
