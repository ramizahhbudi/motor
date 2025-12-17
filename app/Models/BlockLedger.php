<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockLedger extends Model
{
    protected $fillable = [
        'data',
        'timestamp',
        'previous_hash',
        'current_hash',
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
