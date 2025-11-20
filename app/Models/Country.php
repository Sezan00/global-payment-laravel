<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'flag_url',
    ];
    
public function currencies()
{
    return $this->belongsToMany(Currency::class, 'country_currencies')->withTimestamps();
}

public function countryCurrencies()
{
    return $this->hasMany(CountryCurrencies::class);
}

}
