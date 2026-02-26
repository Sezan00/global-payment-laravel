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
        'checkout_session',
        'payment_method',
        'description',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transaction() {
    return $this->belongsTo(Transaction::class);
}
}
