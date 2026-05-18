<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingOutbound extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'outbound_id',
        'outbound_variant_id',
        'schedule_date',
        'schedule_time',
        'number_of_units',
        'participants_per_unit',
        'total_participants',
        'add_documentation',
        'additional_documentation',
        'documentation_fee',
        'need_transportation',
        'transportation_vehicles',
        'transportation_fee',
        'base_price',
        'subtotal',
        'total_price',
        'note',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'schedule_time' => 'datetime:H:i',
        'number_of_units' => 'integer',
        'participants_per_unit' => 'integer',
        'total_participants' => 'integer',
        'add_documentation' => 'boolean',
        'additional_documentation' => 'integer',
        'documentation_fee' => 'decimal:2',
        'need_transportation' => 'boolean',
        'transportation_vehicles' => 'integer',
        'transportation_fee' => 'decimal:2',
        'base_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function outbound(): BelongsTo
    {
        return $this->belongsTo(Outbound::class);
    }

    public function outboundVariant(): BelongsTo
    {
        return $this->belongsTo(OutboundVariant::class);
    }
}
