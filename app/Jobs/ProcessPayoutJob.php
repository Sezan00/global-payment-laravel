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
        //contry code
        $countryCode = $transaction->recipient->sourceContryCurrency->country->iso3;

        logger('Country Code:' . $countryCode);
        Log::info('Country Code', ['country code' => $countryCode]);
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
                'country_code' => $countryCode,
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
        } catch (\Exception $e) {
            // Handle Stripe errors
            Log::error('Stripe Payout Failed: ' . $e->getMessage());
        }
    }
}
