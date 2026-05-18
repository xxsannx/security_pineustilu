<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_type',
        'booking_date',
        'token_code',
        'status',
        'guest_name',
        'guest_phone',
        'guest_email',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'status' => BookingStatus::class,
    ];

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->status->canBeCancelled();
    }

    /**
     * Check if booking can be rescheduled.
     */
    public function canBeRescheduled(): bool
    {
        return $this->status->canBeRescheduled();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function bookingOutbounds(): HasMany
    {
        return $this->hasMany(BookingOutbound::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function reschedules(): HasMany
    {
        return $this->hasMany(Reschedule::class, 'old_book_id');
    }

    public function cancellation(): HasOne
    {
        return $this->hasOne(Cancellation::class);
    }
}
