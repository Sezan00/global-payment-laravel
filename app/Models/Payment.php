<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     protected $table = 'payments';
    protected $fillable = [
        'user_id',
        'amount',
        'reference',
        'transaction_id',
        'status',
        'payment_method',
        'description',
    ];
}
