<?php

namespace App\Helpers;

/**
 * Helper class for currency formatting operations.
 * Centralizes all rupiah/IDR formatting logic to avoid duplication.
 */
class CurrencyHelper
{
    /**
     * Format number as Indonesian Rupiah (IDR).
     *
     * @param float|int|null $amount The amount to format
     * @param string $prefix Prefix to use (default: 'Rp ')
     * @param string $fallback Value to return if amount is null/zero
     * @return string Formatted currency string
     */
    public static function formatRupiah(float|int|null $amount, string $prefix = 'Rp ', string $fallback = '-'): string
    {
        if ($amount === null || $amount == 0) {
            return $fallback;
        }

        return $prefix . number_format($amount, 0, ',', '.');
    }

    /**
     * Format number as IDR (alternative prefix).
     *
     * @param float|int|null $amount The amount to format
     * @param string $fallback Value to return if amount is null/zero
     * @return string Formatted currency string
     */
    public static function formatIdr(float|int|null $amount, string $fallback = '-'): string
    {
        return self::formatRupiah($amount, 'IDR ', $fallback);
    }

    /**
     * Format price with unit suffix (for items like /pax, /set, etc).
     *
     * @param float|int|null $amount The amount to format
     * @param string|null $type The item type (pax, set, kresek, iket, pack, botol)
     * @param bool $useShortFormat Use short format like "98k" for thousands
     * @return string|null Formatted price string or null if amount is null
     */
    public static function formatPriceWithUnit(float|int|null $amount, ?string $type = null, bool $useShortFormat = true): ?string
    {
        if ($amount === null) {
            return null;
        }

        // Short format for clean thousands (e.g., 98000 -> "98k")
        if ($useShortFormat && $amount >= 1000 && fmod($amount, 1000) === 0.0) {
            $display = intval($amount / 1000) . 'k';
        } else {
            // Standard format with thousand separator
            $display = ($amount == intval($amount))
                ? number_format($amount, 0, ',', '.')
                : number_format($amount, 2, ',', '.');
        }

        // Unit suffix mapping
        $unitSuffix = match ($type) {
            'pax' => ' / pax',
            'set' => ' / set',
            'bag' => ' / bag',
            'bundle' => ' / bundle',
            'kresek' => ' / kresek',
            'iket' => ' / iket',
            'pack', 'botol', 'bottle' => '',
            default => '',
        };

        return $display . $unitSuffix;
    }

    /**
     * Parse formatted currency string back to number.
     *
     * @param string $formatted The formatted string (e.g., "Rp 1.000.000")
     * @return float The numeric value
     */
    public static function parseRupiah(string $formatted): float
    {
        // Remove prefix and thousand separators
        $cleaned = preg_replace('/[^0-9,]/', '', $formatted);
        $cleaned = str_replace(',', '.', $cleaned);
        
        return (float) $cleaned;
    }
}
