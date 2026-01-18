<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $fillable = [
        'user_id',
        'wise_recipient_id',
        'target_country_currency_id',
        'source_country_currency_id',
        'receive_type',
        'full_name',
        'relation_id',
        'phone',
        'email',
        'city',
        'address',
        'post_code',
        'bank_name',
        'bank_account',
        'wallet_type',
        'wallet_number',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function contry(){
        return $this->belongsTo(Country::class);
    }

    public function countryCurrency(){
        return $this->belongsTo(CountryCurrencies::class, 'target_country_currency_id');
    }

    public function quotation(){
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function relation(){
        return $this->belongsTo(Relation::class, 'relation_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'recipient_id');
    }

    public function attributes(){
        return $this->hasMany(RecipientAttribute::class, 'recipient_id');
    }
}
