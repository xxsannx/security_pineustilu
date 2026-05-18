<?php

namespace App\Helpers;

use App\Models\Gallery;
use Illuminate\Support\Collection;

class GalleryHelper
{
    /**
     * Get gallery for dashboard by type
     */
    public static function getDashboard(string $type): ?Gallery
    {
        $typeMap = [
            'header' => 'dashboard_header',
            'map' => 'dashboard_map',
            'galeri' => 'dashboard_galeri',
        ];

        $galleryType = $typeMap[$type] ?? $type;

        return Gallery::where('type', $galleryType)
            ->whereNull('area_id')
            ->first();
    }

    /**
     * Get all dashboard galleries by type
     */
    public static function getDashboardAll(string $type): Collection
    {
        $typeMap = [
            'header' => 'dashboard_header',
            'map' => 'dashboard_map',
            'galeri' => 'dashboard_galeri',
        ];

        $galleryType = $typeMap[$type] ?? $type;

        return Gallery::where('type', $galleryType)
            ->whereNull('area_id')
            ->get();
    }

    /**
     * Get gallery for area by type
     */
    public static function getArea(int $areaId, string $type): ?Gallery
    {
        return Gallery::where('area_id', $areaId)
            ->where('type', $type)
            ->first();
    }

    /**
     * Get all galleries for area by type
     */
    public static function getAreaAll(int $areaId, string $type): Collection
    {
        return Gallery::where('area_id', $areaId)
            ->where('type', $type)
            ->get();
    }

    /**
     * Get image URL from gallery
     */
    public static function getImageUrl(?Gallery $gallery, string $default = ''): string
    {
        if (!$gallery) {
            return $default;
        }

        // Check if path starts with 'images/' (legacy/seeded images)
        if (str_starts_with($gallery->image_path, 'images/')) {
            return asset($gallery->image_path);
        }

        // Otherwise use storage path
        return asset('storage/' . $gallery->image_path);
    }

    /**
     * Get dashboard image URL directly
     */
    public static function getDashboardImage(string $type, string $default = ''): string
    {
        $gallery = self::getDashboard($type);
        return self::getImageUrl($gallery, $default);
    }

    /**
     * Get area image URL directly
     */
    public static function getAreaImage(int $areaId, string $type, string $default = ''): string
    {
        $gallery = self::getArea($areaId, $type);
        return self::getImageUrl($gallery, $default);
    }

    /**
     * Get area galleries collection with URLs
     */
    public static function getAreaGalleries(int $areaId, string $type): Collection
    {
        return self::getAreaAll($areaId, $type)->map(function ($gallery) {
            return (object) [
                'id' => $gallery->id,
                'url' => self::getImageUrl($gallery),
                'description' => $gallery->description,
            ];
        });
    }

    /**
     * Get dashboard galleries collection with URLs
     */
    public static function getDashboardGalleries(string $type): Collection
    {
        return self::getDashboardAll($type)->map(function ($gallery) {
            return (object) [
                'id' => $gallery->id,
                'url' => self::getImageUrl($gallery),
                'description' => $gallery->description,
            ];
        });
    }
}
