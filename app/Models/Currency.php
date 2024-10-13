<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'cross_order',
        'code',
        'unit',
        'name',
        'forex_buying',
        'forex_selling',
        'banknote_buying',
        'banknote_selling',
        'cross_rate_usd',
        'cross_rate_other',
        'date',
    ];

    protected $casts = [
        'cross_order' => 'integer',
        'unit' => 'integer',
        'forex_buying' => 'decimal:4',
        'forex_selling' => 'decimal:4',
        'banknote_buying' => 'decimal:4',
        'banknote_selling' => 'decimal:4',
        'cross_rate_usd' => 'decimal:4',
        'cross_rate_other' => 'decimal:4',
        'date' => 'date',
    ];
}
