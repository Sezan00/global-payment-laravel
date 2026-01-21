<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WiseQuote extends Model
{
    protected $fillable = [
        'profile_id',
        'wise_quote_id',
        'source_currency',
        'target_currency',
        'target_amount',
        'status',
        'raw_response',
    ];

      protected $casts = [
        'raw_response' => 'array',
    ];
}
