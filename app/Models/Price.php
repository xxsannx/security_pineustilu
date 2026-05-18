<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'item_id',
        'outbound_id',
        'outbound_variant_id',
        'season_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(AreaUnit::class, 'unit_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function outbound(): BelongsTo
    {
        return $this->belongsTo(Outbound::class);
    }

    public function outboundVariant(): BelongsTo
    {
        return $this->belongsTo(OutboundVariant::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(SeasonDate::class, 'season_id');
    }
}
