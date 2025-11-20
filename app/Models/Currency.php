<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'decimal_places',
    ];

    
    public function countries(){
     return $this->belongsToMany(Country::class, 'country_currencies')
        ->withTimestamps();
}

    public function countryCurrencies()
{
    return $this->hasMany(CountryCurrencies::class);
}

}
