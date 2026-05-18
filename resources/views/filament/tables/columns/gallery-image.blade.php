@php
    $imageUrl = $getRecord()->image_url;
@endphp

<div class="flex items-center justify-center">
    @if($imageUrl)
        <img 
            src="{{ $imageUrl }}" 
            alt="{{ $getRecord()->description ?? 'Gallery Image' }}"
            class="rounded-lg object-cover"
            style="width: 100px; height: 60px;"
            loading="lazy"
        />
    @else
        <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg" style="width: 100px; height: 60px;">
            <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    @endif
</div>
