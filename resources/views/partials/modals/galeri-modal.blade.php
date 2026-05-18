{{-- Lightbox Modal Partial (used by gallery) --}}
<div id="lightbox-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="lightbox-title">
    <div class="relative w-full h-full" tabindex="-1">
        <h2 id="lightbox-title" class="sr-only">Gallery — large image</h2>
        {{-- Close Button --}}
        <button onclick="closeLightbox()"
            class="absolute top-6 right-6 z-[10001] bg-white hover:bg-gray-100 text-gray-800 rounded-full p-3 shadow-xl transition-all duration-300 hover:scale-110 hover:rotate-90 group cursor-pointer">
            <svg class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        {{-- Zoom Controls (top-left) --}}
        <div class="absolute top-6 left-6 z-[10001] flex flex-col gap-2">
            <button onclick="event.stopPropagation(); zoomInLightbox();"
                class="bg-white hover:bg-gray-100 text-gray-800 rounded-full p-3 shadow-xl transition-all duration-300 hover:scale-110 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            <button onclick="event.stopPropagation(); zoomOutLightbox();"
                class="bg-white hover:bg-gray-100 text-gray-800 rounded-full p-3 shadow-xl transition-all duration-300 hover:scale-110 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
            <button onclick="event.stopPropagation(); resetLightboxZoom();"
                class="bg-white hover:bg-gray-100 text-gray-800 rounded-full p-3 shadow-xl transition-all duration-300 hover:scale-110 cursor-pointer"
                title="Reset Zoom">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>

        {{-- Zoom Level Indicator (bottom-left) --}}
        <div class="absolute bottom-6 left-6 z-[10001] bg-white rounded-full px-4 py-2 shadow-xl">
            <span id="lightbox-zoom-level" class="text-sm font-semibold text-gray-800">100%</span>
        </div>

        {{-- Instructions (bottom-right) --}}
        <div class="absolute bottom-6 right-6 z-[10001] bg-white rounded-2xl px-4 py-2 shadow-xl max-w-xs hidden md:block">
            <p class="text-xs text-gray-600">
                <span class="font-semibold">💡 Tips:</span> Scroll to zoom, drag to pan
            </p>
        </div>

        {{-- Image Container --}}
        <div id="lightbox-container" class="absolute inset-0 flex items-center justify-center overflow-auto">
            <img id="lightbox-image" src="" alt="Lightbox"
                class="max-h-[90vh] max-w-[90vw] object-contain select-none cursor-grab rounded-2xl shadow-2xl"
                style="transform-origin: center center;" draggable="false" />
        </div>
    </div>
</div>
