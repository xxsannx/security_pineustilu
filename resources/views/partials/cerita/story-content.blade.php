@props([
    'lang' => 'id',
    'title' => '',
    'content' => [],
    'delay' => 0
])

<article class="story-box hidden" 
         data-lang="{{ $lang }}" 
         lang="{{ $lang }}">
    <div class="py-1 sm:py-2 md:py-4"
        data-aos="fade-up" 
        data-aos-duration="400"
        data-aos-delay="{{ $delay }}"
        data-aos-anchor-placement="top-bottom">
        <h2 class="text-brand-primary font-extrabold text-base sm:text-lg md:text-xl lg:text-2xl mb-2 sm:mb-3 md:mb-4">
            {{ $title }}
        </h2>
        <div class="mt-4 sm:mt-6 md:mt-8 space-y-3 sm:space-y-4 text-sm sm:text-base md:text-lg leading-relaxed text-brand-text">
            @foreach($content as $paragraph)
                <p class="text-justify">{{ $paragraph }}</p>
            @endforeach
        </div>
    </div>
</article>