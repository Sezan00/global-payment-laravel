<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $table = 'quotations';
    protected $fillable = [
        'user_id',
        'source_country_currency_id',
        'target_country_currency_id',
        'amount',
        'exhange_rate_id',
        'status'
    ];

    public function exhangeRate(){
        return $this->belongsTo(ExhangeRate::class);
    }

   public function sourceCurrency() {
    return $this->belongsTo(CountryCurrencies::class, 'source_country_currency_id');
    }

    public function targetCurrency() {
    return $this->belongsTo(CountryCurrencies::class, 'target_country_currency_id');
    }
}
