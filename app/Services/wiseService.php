<?php

namespace App\Services;

use App\Models\User;
use App\Models\Recipient;
use App\Models\Transaction;
use App\Models\WiseQuote;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Nette\Utils\Json;

class WiseService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.sandbox.transferwise.tech/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('WISE_API_TOKEN'),
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }


    public function connectUser(User $user)
    {
        try {
            $response = $this->client->get('v1/me');
            $data = json_decode($response->getBody(), true);

            Log::info('Wise Profile Data', $data);
            if (isset($data['id'])) {
                $user->update(['wise_provider_id' => $data['id']]);
            }
            return $data;
        } catch (\Throwable $e) {
            Log::error('WISE connectUser ERROR', ['message' => $e->getMessage()]);
            throw $e;
        }
    }


    public function createRecipient(Recipient $recipient, array $attrs)
    {
        Log::info('WISE: createRecipient called', ['recipient_id' => $recipient->id]);
        $profileId = $recipient->user->wise_provider_id;

        if (!$profileId) {
            $wiseUser = $this->connectUser($recipient->user);

            $profileId = $wiseUser['id'] ?? 'null';

            if (!$profileId) {
                throw new \Exception("User does not have a valid wise profile id");
            }

            $recipient->user->update(['wise_provider_id' => $profileId]);
            $recipient->user->refresh();
        }


        $payload = [
            'currency' => $recipient->countryCurrency?->currency?->code,
            'type' => $attrs['account_type'],
            'accountHolderName' => $recipient->full_name,
            'details' => array_merge(
                $attrs,
                [
                    'accountNumber' => $recipient->bank_account,
                    'address' => [
                        'country'   => $recipient->countryCurrency?->country?->iso2,
                        'city'      => $recipient->city,
                        'firstLine' => $recipient->address,
                        'postCode'  => $recipient->post_code,
                    ]
                ]
            ),
        ];

        try {
            $response = $this->client->post('v1/accounts', ['json' => $payload]);
            $data     = json_decode($response->getBody(), true);

            if (isset($data['id'])) {
                $recipient->update(['wise_recipient_id' => $data['id']]);
                return $data;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            throw $e;
        }
    }



    public function createQuote(User $user, Recipient $recipient, $amount)
    {

        $payload = [
            'sourceCurrency' => $recipient->sourceContryCurrency?->currency?->code,
            'targetCurrency' => $recipient->countryCurrency?->currency?->code,
            'sourceAmount'   => null,
            'targetAmount'   => $amount,
            'pricingConfiguration' => [
                'fee' => [
                    'type'     => 'OVERRIDE',
                    'variable' => 0.011,
                    'fixed'    => 15.42,
                ]
            ],
            'profile' => $user->wise_provider_id,
        ];
        
        $userWiseId = $user->wise_provider_id;
        logger('User Wise Getting to User Wise', ['userWiseId' => $userWiseId]);
        logger('Quote Payload', $payload);

        $response = $this->client->post('v3/profiles/'.$userWiseId.'/quotes', ['json' => $payload]);
        $data = json_decode($response->getBody(), true);

        // logger('create Quote', $data);
        logger($data['profile'] ?? null);

            
        $wiseQuote = WiseQuote::create([
            'profile_id'      => $user->wise_provider_id,
            'wise_quote_id'   => $data['id'],
            'source_currency' => $data['sourceCurrency'],
            'target_currency' => $data['targetCurrency'],
            'target_amount'   => $data['targetAmount'],
            'status'          => $data['status'] ?? 'PENDING',
            'raw_response'    => $data,
        ]);

        logger('Insert Quote Data', $wiseQuote);
        return $wiseQuote;
    }


    public function createTransfer($quote)
    {
        Log::info('WISE: createTransfer called', ['quote_id' => $quote['id']]);

        $payload = [
            'quote' => $quote['id'],
            'customerTransactionId' => uniqid('tx_'),
            'details' => [
                'reference' => 'Payment via Sandbox',
                'transferPurpose' => 'verification.transfer',
                'sourceOfFunds' => 'other'
            ]
        ];

        $response = $this->client->post('transfers', ['json' => $payload]);
        $data = json_decode($response->getBody(), true);

        Log::info('WISE: createTransfer response', $data);

        return $data;
    }
}
