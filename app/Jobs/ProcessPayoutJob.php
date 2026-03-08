<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http as FacadesHttp;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Http;

class ProcessPayoutJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $transactionId;
    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transaction = Transaction::with(['recipient.sourceContryCurrency.country', 'sourceCountryCurrency.country', 'targetCountryCurrency.currency', 'user'])
            ->where('status', 'process')
            ->findOrFail($this->transactionId);
            // Log::info('Transaction loaded', ['id' => $this->transactionId, 'status' => $transaction->status]);

        
        $user = $transaction->user;
        // logger('user data:' . $user);
        //Source Country code
        $SourceCountryCode = $transaction->recipient->sourceContryCurrency->country->iso3;
        //target currency code
        $targetCurrencyCode = $transaction->recipient->countryCurrency->currency->code;
        //Sorce Currency code 
        $sourceCurrencyCode = $transaction->recipient->sourceContryCurrency->currency->code;
        $recipientID = $transaction->recipient->id;
        $recipient = $transaction->recipient; 
        // logger('Recipient:', ['recipient' => $recipient]);
        //target country code
        $targetCountryCode = $transaction->recipient->countryCurrency->country->iso3;
        // logger('Recipient Target Country Code:' . $targetCountryCode);
        // logger('Target Currency:' . $targetCurrencyCode);
        // logger('Recipient Full Data:' . $recipient);
        // logger('Country Code:' . $SourceCountryCode);

        $bankname =$recipient->bank_name;
        $accountNumber =$recipient->bank_account;
        // Log::info('bank name', ['bank name' => $bankname]);
        logger('Account Number:' . $accountNumber);
        Log::info('Country Code', ['country code' => $SourceCountryCode]);
        if(!$user) Log::error('Transaction has no user');
            if(!$transaction->sourceCountryCurrency || !$transaction->sourceCountryCurrency->country) {
                // Log::error('Country relation missing');
            }
        try {
            if(!$user->individual_id){
               $response = Http::acceptJson()->post('http://127.0.0.1:9000/api/indivisual', [
                'first_name'   => $user->name,
                'last_name'    => 'Molla',
                'email'        => $user->email,
                'country_code' => $SourceCountryCode,
            ]);
                // Log::info('Status Code', ['status' => $response->status()]);
                // Log::info('Response Body', ['body' => $response->body()]);
                if($response->successful()){
                    $individualId = $response->json('id');
                    // logger('Individual ID:' . $individualId);
                    // logger('Status Code:', ['status' => $response->status()]);
                    $user->update([
                        'individual_id' => $individualId
                    ]);
                } else {
                    throw new \Exception('Individual creation failed');
                }
            }
        $destinationResponse = Http::acceptJson()->post('http://127.0.0.1:9000/api/destination', [
                'type'         => 'Bank',
                'recipient_id' => [
                    'individual_id' => $user->individual_id
                ],
                'account' => [
                    'recipient_name' => $recipient->user->name,
                    'bank_name'      => $recipient->bank_account,
                    'account_number'  => $accountNumber,
                 ],
                'currency'     => $targetCurrencyCode,
                'country_code' => $targetCountryCode,
        ]);
          
            logger('Destination API Full Response:', $destinationResponse->json());
            if($destinationResponse->successful()){
                $desinationId = $destinationResponse->json('data.destination_id');
                $recipient->update([
                    'destination_id' => $desinationId
                ]);
            }
         //quote section 
            // $quoteResponse = Http::acceptJson()->post('http://127.0.0.1:9000/api/quote', [
            //     'mode'                   => 'Transaction',
            //     'destination_country'    => $targetCountryCode,
            //     'destination_account_id' => $recipient->user_id,
            //     'sending_currency'       => $SourceCountryCode,
            //     'receiving_currency'     => $targetCurrencyCode, 
                
            // ]);

            //Transaction Section
         $transactionResponse = Http::acceptJson()->post('http://127.0.0.1:9000/api/transfer',[
                'destination_account_id' => $recipient->user_id,
                'user_id'                => $user->id,
                'mode'                   => 'transaction',
                'sending_currency'       => $SourceCountryCode,
                'receiving_currency'     => $targetCurrencyCode,
                'source_of_funds'        => $transaction->sourceOfFund->source_fund,
                'purpose_of_transfer'    =>  $transaction->purposeOfTransfer->purpose_transfer,
                'relationship'           => $transaction->recipient->relation->relation,
            ]);
            // logger('Transaction API Full Response:', $transactionResponse->json());
            if($transactionResponse->successful()){
                $transactionId = $transactionResponse->json('transaction.transaction_id');
                logger('Transaction API ID:', [$transactionId]);
             $update = $transaction->update([
                    'transactions_api_id' => $transactionId
                ]);
                logger('Update result:' . $update);
            }
        } catch (\Exception $e) {
            Log::error('Stripe Payout Failed: ' . $e->getMessage());
        }
    }
}
