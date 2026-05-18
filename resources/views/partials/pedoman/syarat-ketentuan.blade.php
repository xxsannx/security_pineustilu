{{-- Terms & Conditions Section --}}
<section class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden mt-4 sm:mt-6 md:mt-8"
    data-aos="fade-up"
    data-aos-duration="800"
    aria-labelledby="syarat-heading">
    <div class="p-4 sm:p-6 md:p-8 lg:p-10">
        <x-section-heading
            id="syarat-heading"
            title="TERMS & CONDITIONS"
            titleClass="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#017249] tracking-wider" />

        @php
        // Data-driven approach with icon
        $rules = [
        // Column 1
        [
        ['text' => 'Check-in at 2:00 PM & Check-out at 12:00 PM (WIB).', 'icon' => 'checkin.png'],
        ['text' => 'Keep the area clean', 'icon' => 'kebersihan.png'],
        ['text' => 'Music at low volume', 'icon' => 'volume.png'],
        ['text' => 'Smoking prohibited inside tents', 'icon' => 'smoke.png'],
        ['text' => 'No pork or dog food on BBQ & campfire equipment', 'icon' => 'pork.png'],
        ['text' => 'Separate organic and inorganic waste', 'icon' => 'sampah.png'],
        ['text' => 'Immoral activities prohibited', 'icon' => 'immoral.png'],
        ['text' => 'Stay safe & be careful in the camping area', 'icon' => 'safe.png'],
        ],
        // Column 2
        [
        ['text' => 'Drugs & alcohol prohibited', 'icon' => 'drugs.png'],
        ['text' => 'Pineus Tilu is not responsible for loss, damage or left behind items.', 'icon' => 'barang.png'],
        ['text' => 'Dogs prohibited', 'icon' => 'dogs.png'],
        ['text' => 'Karaoke prohibited', 'icon' => 'karaoke.png'],
        ['text' => 'Loudspeakers not allowed', 'icon' => 'sound.png'],
        ['text' => 'No microphones', 'icon' => 'mic.png'],
        ['text' => 'Loud musical instruments not allowed', 'icon' => 'musik.png'],
        ],
        ];
        @endphp

        {{-- Rules Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 md:gap-x-12 gap-y-4 sm:gap-y-6"
            style="font-family: 'Poppins', sans-serif;">

            @foreach($rules as $column)
            <div class="space-y-4 sm:space-y-6">
                @foreach($column as $rule)
                <x-pedoman.rule-item
                    :icon="$rule['icon']"
                    :sub="$rule['sub'] ?? null">
                    {{ $rule['text'] }}
                </x-pedoman.rule-item>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</section>