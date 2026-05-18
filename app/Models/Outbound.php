<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Outbound extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'pricing_type',
        'unit_name',
        'min_participants',
        'max_participants',
        'min_age',
        'duration',
        'distance',
        'has_variants',
        'allows_documentation_addon',
        'requires_transportation',
        'has_camping_package',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'min_participants' => 'integer',
        'max_participants' => 'integer',
        'min_age' => 'integer',
        'duration' => 'integer',
        'distance' => 'decimal:2',
        'has_variants' => 'boolean',
        'allows_documentation_addon' => 'boolean',
        'requires_transportation' => 'boolean',
        'has_camping_package' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['duration_text', 'distance_text'];

    /**
     * Get formatted duration text.
     */
    protected function durationText(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->duration) {
                    return null;
                }
                $mins = (int) $this->duration;
                if ($mins >= 60) {
                    $hours = floor($mins / 60);
                    $remainMins = $mins % 60;
                    return $remainMins > 0 ? "~{$hours}h {$remainMins}m" : "~{$hours} hours";
                }
                return "~{$mins} mins";
            }
        );
    }

    /**
     * Get formatted distance text.
     */
    protected function distanceText(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->distance) {
                    return null;
                }
                return number_format((float) $this->distance, 1) . ' km';
            }
        );
    }

    public function variants(): HasMany
    {
        return $this->hasMany(OutboundVariant::class);
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function bookingOutbounds(): HasMany
    {
        return $this->hasMany(BookingOutbound::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
}
