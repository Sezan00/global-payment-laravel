<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryCurrencies extends Model
{
   protected $fillable = ['country_id', 'currency_id', 'type'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
