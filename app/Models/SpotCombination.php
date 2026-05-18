<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotCombination extends Model
{
    protected $fillable = ['combination_code', 'spots'];

    protected $casts = [
        'spots' => 'array',
    ];
}

