<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhangeRate extends Model
{
    protected $table = 'exhange_rates';

    protected $fillable = [
         'user_id',
         'sender_country_currenciy_id',
         'receiver_country_currenciy_id',
         'ex_rate',
         'amount',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

