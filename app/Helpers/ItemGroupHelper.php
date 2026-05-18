<?php

namespace App\Helpers;

use App\Models\Item;
use Illuminate\Support\Str;

/**
 * Helper class for determining item group/category.
 * Centralizes item grouping logic used across controllers.
 */
class ItemGroupHelper
{
    /**
     * Item types that belong to 'perlengkapan' group.
     */
    public const PERLENGKAPAN_TYPES = ['pax', 'set', 'kresek', 'iket', 'bag', 'bundle'];

    /**
     * Item types that belong to 'daging' group.
     */
    public const DAGING_TYPES = ['pack'];

    /**
     * Item types that belong to 'saus' group.
     */
    public const SAUS_TYPES = ['botol'];

    /**
     * Determine the group for an item based on type and name.
     *
     * @param Item $item The item model
     * @return string The group name (perlengkapan, daging, or saus)
     */
    public static function determineGroup(Item $item): string
    {
        return self::determineGroupFromTypeAndName($item->type, $item->name);
    }

    /**
     * Determine the group based on type and name strings.
     *
     * @param string|null $type The item type
     * @param string|null $name The item name
     * @return string The group name (perlengkapan, daging, or saus)
     */
    public static function determineGroupFromTypeAndName(?string $type, ?string $name): string
    {
        // First, check by type
        if ($type) {
            if (in_array($type, self::PERLENGKAPAN_TYPES, true)) {
                return 'perlengkapan';
            }

            if (in_array($type, self::DAGING_TYPES, true)) {
                return 'daging';
            }

            if (in_array($type, self::SAUS_TYPES, true)) {
                return 'saus';
            }
        }

        // Fallback: check by name keywords (case-insensitive)
        if ($name) {
            $nameLower = strtolower($name);

            if (Str::contains($nameLower, ['saus', 'sauce', 'bbq'])) {
                return 'saus';
            }

            if (Str::contains($nameLower, ['beef', 'sosis', 'meat', 'daging'])) {
                return 'daging';
            }
        }

        // Default group
        return 'perlengkapan';
    }

    /**
     * Get display label for a group.
     *
     * @param string $group The group name
     * @return string Human-readable label
     */
    public static function getGroupLabel(string $group): string
    {
        return match ($group) {
            'perlengkapan' => 'Perlengkapan',
            'daging' => 'Daging & Protein',
            'saus' => 'Saus & Bumbu',
            default => ucfirst($group),
        };
    }

    /**
     * Get all available group names.
     *
     * @return array<string>
     */
    public static function getAllGroups(): array
    {
        return ['perlengkapan', 'daging', 'saus'];
    }
}
