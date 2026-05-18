<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'question',
        'answer',
        'order_index',
    ];

    protected $casts = [
        'order_index' => 'integer',
    ];

    public function scopeOrdered($query)
    {
        return $query
            ->orderBy('order_index')
            ->orderBy('id');
    }
}
