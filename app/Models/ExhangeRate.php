<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhangeRate extends Model
{
    protected $table = 'exhange_rates';

    protected $fillable = [
         'user_id',
         'sender_currency',
         'receiver_currency',
         'converted_amount',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

