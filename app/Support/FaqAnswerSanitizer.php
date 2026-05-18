<?php

namespace App\Support;

use DOMAttr;
use DOMDocument;
use DOMElement;

class FaqAnswerSanitizer
{
    private const ALLOWED_TAGS = ['div', 'p', 'ul', 'ol', 'li', 'strong', 'em', 'a', 'br', 'span'];

    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'target', 'rel', 'class'],
        'div' => ['class'],
        'p' => ['class'],
        'ul' => ['class'],
        'ol' => ['class'],
        'li' => ['class'],
        'span' => ['class'],
    ];

    public static function sanitize(?string $html): string
    {
        if (!is_string($html) || trim($html) === '') {
            return '';
        }

        $internalErrors = libxml_use_internal_errors(true);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<div>' . $html . '</div>';

        $dom->loadHTML(
            '<?xml encoding="utf-8" ?>' . $wrappedHtml,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        if ($dom->documentElement instanceof DOMElement) {
            self::sanitizeNode($dom->documentElement);
        }

        $sanitized = '';
        if ($dom->documentElement instanceof DOMElement) {
            foreach ($dom->documentElement->childNodes as $childNode) {
                $sanitized .= $dom->saveHTML($childNode);
            }
        }

        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        return $sanitized;
    }

    private static function sanitizeNode(\DOMNode $node): void
    {
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);
            if ($child !== null) {
                self::sanitizeNode($child);
            }
        }

        if (!$node instanceof DOMElement) {
            return;
        }

        $tag = strtolower($node->tagName);

        if (!in_array($tag, self::ALLOWED_TAGS, true)) {
            self::unwrapNode($node);
            return;
        }

        $allowedAttributes = self::ALLOWED_ATTRIBUTES[$tag] ?? [];
        $attributesToRemove = [];

        foreach ($node->attributes as $attribute) {
            if (!$attribute instanceof DOMAttr) {
                continue;
            }

            $attributeName = strtolower($attribute->name);
            if (!in_array($attributeName, $allowedAttributes, true)) {
                $attributesToRemove[] = $attribute->name;
            }
        }

        foreach ($attributesToRemove as $attributeName) {
            $node->removeAttribute($attributeName);
        }

        if ($tag === 'a') {
            self::sanitizeAnchor($node);
        }
    }

    private static function sanitizeAnchor(DOMElement $anchor): void
    {
        $href = trim((string) $anchor->getAttribute('href'));

        if ($href === '' || !self::isSafeUrl($href)) {
            $anchor->removeAttribute('href');
        }

        $target = strtolower(trim((string) $anchor->getAttribute('target')));
        if ($target !== '_blank') {
            $anchor->removeAttribute('target');
            $anchor->removeAttribute('rel');
            return;
        }

        $anchor->setAttribute('target', '_blank');
        $anchor->setAttribute('rel', 'noopener noreferrer');
    }

    private static function isSafeUrl(string $url): bool
    {
        $url = trim($url);
        if ($url === '') {
            return false;
        }

        if (
            str_starts_with($url, '/') ||
            str_starts_with($url, '#') ||
            str_starts_with($url, './') ||
            str_starts_with($url, '../')
        ) {
            return true;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto', 'tel'], true);
    }

    private static function unwrapNode(DOMElement $node): void
    {
        $parent = $node->parentNode;
        if ($parent === null) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }
}
