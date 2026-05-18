<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $extra_charge_full
 * @property float $extra_charge_breakfast
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Facility> $facilities
 * @property-read Collection<int, Gallery> $galleries
 * @property-read Collection<int, AreaUnit> $areaUnits
 */
class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'extra_charge_full',
        'extra_charge_breakfast',
    ];

    protected $casts = [
        'extra_charge_full' => 'decimal:2',
        'extra_charge_breakfast' => 'decimal:2',
    ];

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function areaUnits(): HasMany
    {
        return $this->hasMany(AreaUnit::class);
    }
}
