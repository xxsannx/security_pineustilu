<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FacilityIconHelper
{
    /**
     * Icon directory path constant
     */
    private const ICON_PATH = 'images/icons/area/';

    /**
     * Cache for available icon files
     */
    private static ?array $cachedFiles = null;

    /**
     * Get bedroom and sleeping facility mappings
     */
    private static function getBedroomMappings(): array
    {
        return [
            'Foam Mattress' => 'kasur-busa.png',
            'King bed' => 'king-bed.png',
            'Pillow' => 'bantal.png',
            'Premium Pillow' => 'bantal.png',
            'Sleeping Bag' => 'kantong-tidur.png',
            'Blanket' => 'kantong-tidur.png',
            'Breakfast' => 'sarapan.png',
        ];
    }

    /**
     * Get seating and furniture mappings
     */
    private static function getSeatingMappings(): array
    {
        return [
            'Floor Cushion' => 'kursi-bantal.png',
            'Beanbag' => 'beanbag.png',
            'Rattan Chair' => 'kursi-rotan.png',
            'Wooden Chair' => 'kursi-kayu.png',
            'Log Stool' => 'kursi-kayu.png',
            'Swing Chair' => 'kursi-ayun.png',
            'Bench' => 'bangku.png',
            'Benches' => 'stool.png',
            'Premium Sofa with Pillows' => 'sofa.png',
            'Premium Sofa' => 'sofa.png',
            'Sofa Sofabed' => 'sofa.png',
            'Sofabed' => 'sofa.png',
            'Sofa' => 'sofa.png',
        ];
    }

    /**
     * Get table mappings
     */
    private static function getTableMappings(): array
    {
        return [
            'Private Dining Table with 6 Benches' => 'meja-stool.png',
            'Private Dining Table with 4 Chairs' => 'meja-stool.png',
            'Private Dining Table with 4 Floor Cushions' => 'meja-leseh.svg',
            'Long Solid Wood Luxury Table' => 'meja-kayu.png',
            'Solid Wood Dining Table' => 'meja-kayu.png',
            'Private Dining Table' => 'meja-kayu.png',
            'Kitchen Island' => 'meja-kayu.png',
            'Large Communal Table' => 'meja-umum.svg',
            'Large Shared Table' => 'meja-umum.svg',
            'Shared Dining Table' => 'meja-umum.svg',
            '5m Long Table' => 'meja-panjang5m.png',
            'Long Table' => 'meja-panjang5m.png',
            'Luxury Table' => 'meja-panjang5m.png',
            'Dining Table' => 'meja-panjang5m.png',
            'Coffee Table' => 'meja-kopi.png',
            'Console Table' => 'meja-konsol.png',
            'Console' => 'meja-konsol.png',
            'Bar Table' => 'meja-bar.png',
        ];
    }

    /**
     * Get lighting, electricity, and hammock mappings
     */
    private static function getAmenitiesMappings(): array
    {
        return [
            'Power Outlet' => 'terminal.png',
            'Indoor & Outdoor Lights' => 'lampu.png',
            'Indoor & Outdoor Lamp' => 'lampu.png',
            'Decorative Hanging Lamp' => 'lampu.png',
            'Hanging Lamp' => 'lampu.png',
            'Bedside Lamp' => 'lampu-meja.png',
            'Table Lamp' => 'lampu-meja.png',
            'Lamp' => 'lampu.png',
            'Net Hammock' => 'hammock-jaring.png',
            'Standing Hammock' => 'standing.svg',
            'Hammock Swing' => 'hammock.png',
            'Hammock' => 'hammock.png',
            'Bamboo Mat' => 'tikar-bambu.png',
            'Luxury Carpet' => 'karpet.png',
            'Comfortable Carpet' => 'karpet.png',
            'Carpet' => 'karpet.png',
            'Mat' => 'tikar.png',
        ];
    }

    /**
     * Get bathroom and toiletries mappings
     */
    private static function getBathroomMappings(): array
    {
        return [
            'Bathroom with Water Heater' => 'shower.png',
            'Bathroom with Hot Water' => 'shower.png',
            'Premium Hot Water Shower' => 'shower.png',
            'Hot Water Shower' => 'shower.png',
            'Private Bathroom' => 'shower.png',
            'Shower' => 'shower.png',
            'Freestanding Bathtub' => 'bak-mandi.png',
            'Bathtub' => 'bak-mandi.png',
            'Modern Sitting Toilet' => 'toilet.png',
            'Sitting Toilet' => 'toilet.png',
            'Toilet' => 'toilet.png',
            'Double Washbasin' => 'wastafel-bulat.png',
            'Washbasin' => 'wastafel.png',
            'Toothbrushes with Toothpaste' => 'sikat-gigi.png',
            'Toothbrush' => 'sikat-gigi.png',
            'Toothpaste' => 'sikat-gigi.png',
            '8 Pax all extras' => 'sikat-gigi.png',
            'Premium Towel' => 'handuk.png',
            'Towel' => 'handuk.png',
            'Premium Toiletries' => 'sabun.png',
            'Toiletries' => 'sabun.png',
            'Shampoo' => 'sampo.png',
            'Soap' => 'sabun.png',
            'Hair Dryer' => 'kipas.png',
        ];
    }

    /**
     * Get kitchen and dining mappings
     */
    private static function getKitchenMappings(): array
    {
        return [
            'Complete Kitchen Equipment' => 'peralatan-dapur.png',
            'Kitchen Equipment' => 'peralatan-dapur.png',
            'Pantry with table and sink' => 'pantry.png',
            'Complete Kitchen' => 'kompor.png',
            'Cooking Utensils' => 'peralatan-bbq.png',
            'Dining Utensils' => 'alat-makan.png',
            'Dinnerware' => 'alat-makan.png',
            'Refrigerator' => 'kulkas.png',
            'Gas Stove' => 'kompor.png',
            'Electric Kettle' => 'dispenser.png',
            'Coffee Maker' => 'dispenser.png',
            'Rice Cooker' => 'kompor.png',
            'Microwave' => 'kompor.png',
            'Pantry' => 'meja-pantri.png',
            'Kitchen' => 'kompor.png',
            'Fridge' => 'kulkas.png',
            'Stove' => 'kompor.png',
            'Hot & Cold Water Dispenser' => 'dispenser.png',
            'Drinking Water & Dispenser' => 'dispenser.png',
            'Drinking Water' => 'dispenser.png',
            'Dispenser' => 'dispenser.png',
            'Water Jug' => 'dispenser.png',
            'Water Gallon' => 'galon.png',
        ];
    }

    /**
     * Get outdoor facilities mappings
     */
    private static function getOutdoorMappings(): array
    {
        return [
            'Bonfire + Bonfire Grill' => 'apiunggun.png',
            'Bonfire' => 'apiunggun.png',
            'Fire Pit' => 'apiunggun.png',
            'Complete BBQ' => 'bbq.png',
            'BBQ Equipment' => 'peralatan-bbq.png',
            'BBQ Area' => 'bbq.png',
            'BBQ Grill' => 'bbq.png',
            'BBQ' => 'bbq.png',
            'Grill' => 'peralatan-bbq.png',
            'Children\'s Play Pool' => 'pool.png',
            'Play Pool' => 'pool.png',
            'Pool' => 'pool.png',
            'Spacious Hanging Roofed Terrace' => 'teras-gantung.png',
            'Hanging Roofed Terrace' => 'teras-gantung.png',
            'Terrace' => 'teras-gantung.png',
            'Family Tent' => 'tenda.png',
            'All Tents' => 'tenda.png',
            'Tent' => 'tenda.png',
        ];
    }

    /**
     * Get miscellaneous facility mappings
     */
    private static function getMiscMappings(): array
    {
        return [
            'Smart TV' => 'tv.png',
            'Television' => 'tv.png',
            'TV' => 'tv.png',
            'Wifi with Special Router' => 'wifi.png',
            'High-Speed WiFi' => 'wifi.png',
            'Router' => 'wifi.png',
            'WiFi' => 'wifi.png',
            'Wifi' => 'wifi.png',
            'Standing Clothes Rack' => 'gantungan.png',
            'Clothes Rack' => 'gantungan.png',
            'Storage Box' => 'storage.svg',
            'Shoe Rack' => 'rak-sepatu.png',
            'Wardrobe' => 'lemari.png',
            'Cabinet' => 'lemari.png',
            'Closet' => 'lemari.png',
            'CCTV' => 'cctv.png',
            'Wooden Statue' => 'patung-kayu.png',
            'Painting' => 'lukisan.png',
            'Statue' => 'patung-kayu.png',
            'Mirror' => 'cermin.png',
            'Exhaust Fan' => 'kipas.png',
            'Room Heater' => 'kipas.png',
            'Cooler' => 'freezer.png',
            'Fan' => 'kipas.png',
            'AC' => 'kipas.png',
            'Welcome Drink' => 'pitcher.png',
            'Pitcher' => 'pitcher.png',
            'Trash Bin' => 'tempat-sampah.png',
            'Trash' => 'tempat-sampah.png',
        ];
    }

    /**
     * Get the comprehensive icon mapping for facility names.
     * Maps facility keywords to icon filenames.
     */
    public static function getIconMap(): array
    {
        return array_merge(
            self::getBedroomMappings(),
            self::getSeatingMappings(),
            self::getTableMappings(),
            self::getAmenitiesMappings(),
            self::getBathroomMappings(),
            self::getKitchenMappings(),
            self::getOutdoorMappings(),
            self::getMiscMappings()
        );
    }

    /**
     * Get available icon files from the directory
     */
    private static function getAvailableFiles(): array
    {
        if (self::$cachedFiles !== null) {
            return self::$cachedFiles;
        }

        $iconDir = public_path('images/icons/area');
        self::$cachedFiles = [];
        
        if (is_dir($iconDir)) {
            $files = array_diff(scandir($iconDir), ['.', '..']);
            self::$cachedFiles = array_values($files);
        }

        return self::$cachedFiles;
    }

    /**
     * Normalize facility name for better matching
     */
    private static function normalizeName(string $name): string
    {
        // Remove numbers and content in parentheses
        $normalized = preg_replace('/\s*\([^)]*\)/', '', $name);
        $normalized = preg_replace('/\b\d+\b/', '', $normalized);
        return trim(str_replace('  ', ' ', $normalized));
    }

    /**
     * Try to find icon using phrase matching
     */
    private static function findByPhraseMatch(string $name, string $normalizedName, array $sortedKeys, array $iconMap, array $availableFiles): ?string
    {
        // Try exact phrase match in original name
        foreach ($sortedKeys as $key) {
            $iconFile = $iconMap[$key];
            if (stripos($name, $key) !== false && in_array($iconFile, $availableFiles)) {
                return self::ICON_PATH . $iconFile;
            }
        }

        // Try match with normalized name
        foreach ($sortedKeys as $key) {
            $iconFile = $iconMap[$key];
            if (stripos($normalizedName, $key) !== false && in_array($iconFile, $availableFiles)) {
                return self::ICON_PATH . $iconFile;
            }
        }

        return null;
    }

    /**
     * Try to find icon using slug-based filename matching
     */
    private static function findBySlugMatch(string $name, string $normalizedName, array $availableFiles): ?string
    {
        $extensions = ['svg', 'png', 'webp', 'jpg', 'jpeg'];
        $candidates = array_unique([
            Str::slug($name),
            Str::slug($normalizedName),
        ]);
        
        foreach ($candidates as $candidate) {
            $candidate = trim($candidate, '-');
            if (empty($candidate)) {
                continue;
            }
            
            foreach ($extensions as $ext) {
                $filename = "{$candidate}.{$ext}";
                if (in_array($filename, $availableFiles)) {
                    return self::ICON_PATH . $filename;
                }
            }
        }

        return null;
    }

    /**
     * Try to find icon using keyword matching
     */
    private static function findByKeywordMatch(string $name, array $availableFiles): ?string
    {
        $keywords = array_filter(
            preg_split('/[^A-Za-z0-9]+/', $name),
            fn($word) => strlen($word) > 2
        );
        
        foreach ($availableFiles as $file) {
            $fileLower = strtolower($file);
            foreach ($keywords as $keyword) {
                if (stripos($fileLower, $keyword) !== false) {
                    return self::ICON_PATH . $file;
                }
            }
        }

        return null;
    }

    /**
     * Find icon for a facility name.
     */
    public static function findIcon(string $name, array $availableFiles = []): ?string
    {
        $iconMap = self::getIconMap();
        $availableFiles = empty($availableFiles) ? self::getAvailableFiles() : $availableFiles;
        
        if (empty($availableFiles)) {
            return null;
        }

        $normalizedName = self::normalizeName($name);
        
        // Sort icon map keys by length (longest first) for more specific matches
        $sortedKeys = array_keys($iconMap);
        usort($sortedKeys, fn($a, $b) => strlen($b) - strlen($a));

        // Try different matching strategies
        return self::findByPhraseMatch($name, $normalizedName, $sortedKeys, $iconMap, $availableFiles)
            ?? self::findBySlugMatch($name, $normalizedName, $availableFiles)
            ?? self::findByKeywordMatch($name, $availableFiles);
    }

    /**
     * Assign icons to an array of facilities.
     */
    public static function assignIcons(array &$facilities): void
    {
        $availableFiles = self::getAvailableFiles();

        foreach ($facilities as &$facility) {
            if (empty($facility['icon'])) {
                $facility['icon'] = self::findIcon($facility['name'], $availableFiles);
            }
        }
        
        unset($facility);
    }

    /**
     * Clear the cached files (useful for testing or when files are added/removed)
     */
    public static function clearCache(): void
    {
        self::$cachedFiles = null;
    }
}
