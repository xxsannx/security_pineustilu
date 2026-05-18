<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Helpers\ItemGroupHelper;
use App\Models\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Controller for Items/Amenities page (Barang Tambahan).
 * Displays items grouped by category with cached data.
 */
class ItemController extends Controller
{
    /**
     * Cache TTL in seconds (5 minutes).
     */
    private const CACHE_TTL = 300;

    /**
     * Display items page grouped by category.
     *
     * @return View
     */
    public function index(): View
    {
        $rows = Cache::remember('items_with_prices_v2', self::CACHE_TTL, function () {
            return $this->fetchAndMapItems();
        });

        $collection = collect($rows)->groupBy('group');

        return view('barang-tambahan', [
            'perlengkapan' => $this->sortByName($collection->get('perlengkapan', [])),
            'daging' => $this->sortByName($collection->get('daging', [])),
            'saus' => $this->sortByName($collection->get('saus', [])),
        ]);
    }

    /**
     * Fetch items from database and map to display format.
     *
     * @return array<int, array{id:int,name:string,description:mixed,type:mixed,price:float|null,price_display:string|null,group:string}>
     */
    private function fetchAndMapItems(): array
    {
        return Item::with('prices')
            ->get()
            ->map(function (Item $item) {
                $latest = $item->prices->sortByDesc('created_at')->first();
                $rawPrice = $latest && is_numeric($latest->price) ? (float) $latest->price : null;

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'type' => $item->type,
                    'price' => $rawPrice,
                    'price_display' => CurrencyHelper::formatPriceWithUnit($rawPrice, $item->type),
                    'group' => ItemGroupHelper::determineGroup($item),
                ];
            })
            ->toArray();
    }

    /**
     * Sort items array by name (case-insensitive).
     *
     * @param mixed $items Collection or array of items
     * @return array Sorted array
     */
    private function sortByName($items): array
    {
        return collect($items)
            ->sortBy(fn($r) => strtolower($r['name'] ?? ''))
            ->values()
            ->all();
    }
}
