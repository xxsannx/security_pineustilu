<section class="faq-list" aria-label="FAQ list">
    @forelse(($faqs ?? collect()) as $faq)
        <div class="faq-item" data-keywords="{{ $faq['keywords'] }}" aria-open="false">
            <button class="faq-q" aria-expanded="false">
                <span class="flex items-center gap-4">
                    <span class="icon-area w-12 h-12 flex items-center justify-center rounded-md bg-green-50 text-green-700 flex-shrink-0">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $faq['icon_path'] !!}</svg>
                    </span>
                    <span class="faq-title">{{ $faq['question'] }}</span>
                </span>
                <svg class="icon-chevron" viewBox="0 0 24 24" width="22" height="22"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="faq-a text-[13px] sm:text-sm leading-relaxed text-gray-700 [&_p]:my-2 [&_ul]:my-2 [&_ul]:ml-5 [&_ul]:list-disc [&_ol]:my-2 [&_ol]:ml-5 [&_ol]:list-decimal [&_li]:my-1 [&_a]:text-emerald-600 [&_a]:underline [&_em]:italic" hidden>
                {!! $faq['answer_html'] !!}
            </div>
        </div>
    @empty
        <div class="faq-item" aria-open="false">
            <button class="faq-q" aria-expanded="false" disabled>
                <span class="flex items-center gap-4">
                    <span class="icon-area w-12 h-12 flex items-center justify-center rounded-md bg-green-50 text-green-700 flex-shrink-0">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle></svg>
                    </span>
                    <span class="faq-title">No FAQ data available yet.</span>
                </span>
                <svg class="icon-chevron" viewBox="0 0 24 24" width="22" height="22"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    @endforelse
</section>