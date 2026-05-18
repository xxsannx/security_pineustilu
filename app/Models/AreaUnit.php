<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $area_id
 * @property string $name
 * @property int $default_people
 * @property int $max_people
 * @property string|null $tent_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Area $area
 * @property-read Collection<int, BookingDetail> $bookingDetails
 * @property-read Collection<int, Price> $prices
 */
class AreaUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'name',
        'default_people',
        'max_people',
        'tent_type',
    ];

    protected $casts = [
        'default_people' => 'integer',
        'max_people' => 'integer',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class, 'unit_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'unit_id');
    }
}
