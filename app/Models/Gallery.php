<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int|null $area_id
 * @property int|null $facility_id
 * @property int|null $outbound_id
 * @property string $image_path
 * @property string|null $description
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $image_url
 * @property-read Area|null $area
 * @property-read Facility|null $facility
 * @property-read Outbound|null $outbound
 */
class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'facility_id',
        'outbound_id',
        'image_path',
        'description',
        'type',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the full URL for the image
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        // Check if image exists in public folder
        if (file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }

        // Check if image exists in storage
        $disk = Storage::disk('public');
        if ($disk->exists($this->image_path)) {
            // @phpstan-ignore-next-line
            return $disk->url($this->image_path);
        }

        return null;
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function outbound(): BelongsTo
    {
        return $this->belongsTo(Outbound::class);
    }
}
