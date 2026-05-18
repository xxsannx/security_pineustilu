<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\FaqAnswerSanitizer;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $faqMeta = config('faq.meta', []);
        $defaultIconPath = (string) config('faq.default_icon_path', '');

        $faqs = Faq::query()
            ->ordered()
            ->get()
            ->map(function (Faq $faq) use ($faqMeta, $defaultIconPath) {
                $meta = $faqMeta[$faq->slug] ?? [];

                return [
                    'question' => $faq->question,
                    'answer_html' => FaqAnswerSanitizer::sanitize($faq->answer),
                    'keywords' => (string) ($meta['keywords'] ?? Str::of(strip_tags(($faq->question ?? '') . ' ' . ($faq->answer ?? '')))
                        ->lower()
                        ->squish()),
                    'icon_path' => (string) ($meta['icon_path'] ?? $defaultIconPath),
                ];
            });

        return view('faq', compact('faqs'));
    }
}
