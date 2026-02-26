<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'quotation_id',
        'transactions_api_id',
        'recipient_id',
        'payment_id',
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
        public function purposeOfTransfer(){
            return $this->belongsTo(PurposeTransfer::class, 'purpose_of_transfer_id', 'id');
        }
        public function sourceOfFund(){
            return $this->belongsTo(SourceFunds::class, 'source_of_fund_id', 'id');
        }

        public function user(){
            return $this->belongsTo(User::class, 'user_id', 'id');
        }

        public function payment(){
            return $this->hasMany(Payment::class);
        }

        public function targetCountryCurrency(){
            return $this->belongsTo(CountryCurrencies::class, 'target_country_currency_id');
        }

        public function sourceCountryCurrency(){
            return $this->belongsTo(CountryCurrencies::class, 'source_country_currency_id');
        }

  
}
