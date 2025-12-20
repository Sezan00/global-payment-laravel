<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'quotation_id',
        'recipient_id',
        'user_id',
        'source_country_currency_id',
        'purpose_of_transfer_id',
        'source_of_fund_id',
        'target_country_currency_id',
        'amount',
        'rate',
        'converted_amount',
        'status'
    ];
}
