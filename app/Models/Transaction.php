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
        'status',
        'wise_quote_id',
        'wise_transfer_id',
        'wise_recipient_id',
        'wise_status',
        'wise_error',
   ];
        public function quotation(){
            return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
        }

        public function recipient(){
            return $this->belongsTo(Recipient::class, 'recipient_id', 'id');
        }

        public function sourceOfFund(){
            return $this->belongsTo(SourceFunds::class, 'source_of_fund_id', 'id');
        }

        public function user(){
            return $this->belongsTo(User::class, 'user_id', 'id');
        }

  
}
