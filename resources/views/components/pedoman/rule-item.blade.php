@props(['icon' => null, 'sub' => null])

<div class="flex items-center gap-2 sm:gap-3" style="font-family: 'Poppins', sans-serif;">
    {{-- Icon --}}
    <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center">
        @if($icon)
            <img src="{{ asset('images/icons/pedoman/' . $icon) }}" 
                 alt="icon" 
                 class="w-full h-full object-contain">
        @endif
    </div>
    
    {{-- Text Content --}}
    <div class="flex-1">
        <div class="text-[#017249] font-medium leading-relaxed text-xs sm:text-sm md:text-base lg:text-lg">
            {{ $slot }}
        </div>
        @if($sub)
            <div class="text-[#017249] font-bold text-xs sm:text-sm md:text-base lg:text-lg mt-1">{{ $sub }}</div>
        @endif
    </div>
</div>