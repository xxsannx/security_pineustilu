<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class OtpVerification extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'phone_number',
        'otp_hash',
        'expired_at',
        'attempts',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Get the prunable model query for automatic OTP garbage collection.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::where('expired_at', '<', now());
    }
}
