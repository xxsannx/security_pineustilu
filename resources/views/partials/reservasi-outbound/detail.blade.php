<section class="bg-white rounded-xl shadow-md p-6 border-2 border-[#0b5a3e]/10">
    <h2 class="text-xl font-semibold text-[#017249] mb-6">Detail Reservasi</h2>

    <!-- Activity Selector: Pilih Aktivitas -->
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Pilih Aktivitas</h3>
        <div id="outSelector">
            <!-- Track + knob (styled with Tailwind) -->
            <div id="outTrack" class="relative h-[6px] bg-[#e6f6ef] rounded-full" aria-hidden="true">
                <span id="outKnob"
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-5 h-5 bg-[#017249] rounded-full shadow-[0_2px_6px_rgba(1,50,30,0.18)] transition-[left] duration-[320ms]"></span>
            </div>

            <!-- Kategori (Tailwind grid) -->
            <div id="outCatList"
                class="grid grid-cols-3 sm:grid-cols-6 gap-2 mt-4 select-none w-full items-start"
                role="tablist"
                aria-label="Select Outbound">
                @foreach($outbounds as $outbound)
                <button
                    type="button"
                    class="cat-btn justify-self-center border-0 px-3 py-3 text-[#017249] font-extrabold cursor-pointer text-center rounded-xl transition duration-200 min-w-[100px] bg-[#e7f4ef] hover:bg-[#d9efe7] focus:outline-none focus:ring-2 focus:ring-[#017249]/40 aria-pressed:bg-[#017249] aria-pressed:text-white aria-pressed:shadow-[0_2px_8px_rgba(1,50,30,0.12)]"
                    role="tab"
                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                    data-key="{{ $outbound->slug }}"
                    data-outbound-id="{{ $outbound->id }}"
                    data-pricing-type="{{ $outbound->pricing_type }}"
                    data-unit-name="{{ $outbound->unit_name }}"
                    data-min-participants="{{ $outbound->min_participants }}"
                    data-max-participants="{{ $outbound->max_participants }}"
                    data-min-age="{{ $outbound->min_age }}"
                    data-duration="{{ $outbound->duration }}"
                    data-has-variants="{{ $outbound->has_variants ? 'true' : 'false' }}"
                    data-allows-documentation="{{ $outbound->allows_documentation_addon ? 'true' : 'false' }}"
                    data-requires-transportation="{{ $outbound->requires_transportation ? 'true' : 'false' }}">
                    <div class="text-[10px] leading-tight">OUTBOUND</div>
                    <div class="text-sm font-extrabold">{{ strtoupper($outbound->name) }}</div>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="mb-6">
        <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200 overflow-hidden">
            <div id="outMainImage"
                class="w-full aspect-[4/3] flex items-center justify-center bg-[#f2f7f5] rounded-lg overflow-hidden">
                @if($outbounds->first() && $outbounds->first()->galleries->count() > 0)
                    @php
                        $imagePath = $outbounds->first()->galleries->first()->image_path;
                        $imgSrc = str_starts_with($imagePath, 'images/') ? asset($imagePath) : asset('storage/' . $imagePath);
                    @endphp
                    <img src="{{ $imgSrc }}" 
                         alt="{{ $outbounds->first()->name }}" 
                         class="w-full h-full object-contain">
                @else
                    <span class="text-gray-400">Pilih aktivitas untuk melihat galeri</span>
                @endif
            </div>
            
            <!-- Thumbnails -->
            <div id="outThumbs" class="flex flex-wrap gap-2 mt-3">
                @if($outbounds->first() && $outbounds->first()->galleries->count() > 0)
                    @foreach($outbounds->first()->galleries->take(6) as $gallery)
                    @php
                        $thumbPath = $gallery->image_path;
                        $thumbSrc = str_starts_with($thumbPath, 'images/') ? asset($thumbPath) : asset('storage/' . $thumbPath);
                    @endphp
                    <button type="button" class="w-16 h-12 rounded-lg bg-white border border-gray-200 overflow-hidden hover:ring-2 hover:ring-[#017249] transition"
                         data-image="{{ $thumbSrc }}">
                        <img src="{{ $thumbSrc }}" alt="" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Info Link -->
        <div class="mt-3">
            <button id="outInfoBtn" type="button"
                class="flex items-center gap-2 text-sm text-[#017249] underline decoration-dotted cursor-pointer hover:text-[#0b5a3e] transition-colors">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-green-200 text-[#017249] text-xs">i</span>
                Klik untuk informasi detail aktivitas
            </button>
        </div>
    </div>

    <!-- Variant Selection (for activities with variants) -->
    <div id="variantSection" class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/10 mb-6 hidden">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pilih Variant
            </h3>
        </div>
        <div id="variantGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <!-- Variants will be populated dynamically -->
        </div>
    </div>

    <!-- Date Selection & Participants -->
    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/10 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Tanggal & Peserta
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Date Selection -->
            <div class="space-y-2">
                <label class="block text-xs text-gray-500" for="outCheckin">Tanggal Aktivitas</label>
                <input id="outCheckin" name="activity_date" type="date"
                    class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 bg-white focus:border-[#017249] focus:ring-2 focus:ring-[#017249]/20 transition-all duration-200 text-gray-700 font-medium text-sm"
                    min="{{ date('Y-m-d') }}" />
            </div>

            <!-- Participants -->
            <div class="space-y-2">
                <label class="block text-xs text-gray-500" id="guestLabel">Jumlah Peserta</label>
                <div class="flex items-center gap-2">
                    <button type="button" id="outGuestDecrease" aria-label="Decrease participants"
                        class="w-9 h-9 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <input type="text" id="outGuestCount" name="guest_count" value="{{ $outbounds->first()->min_participants ?? 1 }}" readonly
                        class="w-14 text-center rounded-xl border-2 border-gray-200 px-2 py-2 bg-white text-gray-700 font-bold text-lg"
                        aria-live="polite">
                    <button type="button" id="outGuestIncrease" aria-label="Increase participants"
                        class="w-9 h-9 rounded-full border-2 border-[#017249] bg-white text-[#017249] hover:bg-[#017249] hover:text-white transition-all duration-200 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <span id="guestUnit" class="text-xs text-gray-500">orang</span>
                </div>
                <p id="outGuestInfo" class="text-[10px] text-gray-500">
                    @if($outbounds->first())
                        Min: {{ $outbounds->first()->min_participants ?? 1 }}
                        @if($outbounds->first()->max_participants)
                            | Max: {{ $outbounds->first()->max_participants }}
                        @endif
                        @if($outbounds->first()->min_age)
                            | Usia min: {{ $outbounds->first()->min_age }} tahun
                        @endif
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Extra Add-ons -->
    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-4 border border-[#017249]/10 mb-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Extra Add-ons
            </h3>
            <span id="extrasCount" class="text-xs bg-[#017249] text-white px-2 py-0.5 rounded-full font-bold">0</span>
        </div>
        <p class="text-xs text-gray-600 mb-3">Tambahan untuk melengkapi pengalaman outbound Anda</p>
        
        <div class="space-y-2" id="extrasContainer">
            <!-- Dokumentasi Foto/Video -->
            <label id="dokumentasiOption" class="group flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer {{ $outbounds->first() && $outbounds->first()->allows_documentation_addon ? '' : 'hidden' }}">
                <input type="checkbox" name="extras[]" value="dokumentasi" data-price="100000"
                    class="extra-checkbox w-4 h-4 text-[#017249] border-gray-300 rounded focus:ring-[#017249]">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800">Dokumentasi Foto/Video</div>
                    <div class="text-xs text-gray-500">+Rp 100.000/boat</div>
                </div>
            </label>

            <!-- Transportasi -->
            <label id="transportasiOption" class="group flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 hover:border-[#017249]/30 hover:bg-[#017249]/5 transition-all duration-200 cursor-pointer {{ $outbounds->first() && $outbounds->first()->requires_transportation ? '' : 'hidden' }}">
                <input type="checkbox" name="extras[]" value="transportasi" data-price="{{ $transportationPrice->price ?? 200000 }}"
                    class="extra-checkbox w-4 h-4 text-[#017249] border-gray-300 rounded focus:ring-[#017249]">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8m-8 4h4m-6 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800">Transportasi</div>
                    <div class="text-xs text-gray-500">+Rp {{ number_format($transportationPrice->price ?? 200000, 0, ',', '.') }}</div>
                </div>
            </label>
        </div>
    </div>

    <!-- Hidden data container -->
    <div id="outbound-data" data-outbounds="{{ $outbounds->keyBy('id')->toJson() }}" data-transportation-price="{{ $transportationPrice->price ?? 200000 }}" style="display: none;"></div>
</section>

<!-- Pass data to JavaScript -->
<script>
    window.outboundsData = JSON.parse(document.getElementById('outbound-data').dataset.outbounds);
    window.transportationPrice = parseInt(document.getElementById('outbound-data').dataset.transportationPrice);
</script>
