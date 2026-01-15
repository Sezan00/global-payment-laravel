<?php

namespace App\Services;

use App\Models\User;
use App\Models\Recipient;
use App\Models\Transaction;
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

        logger('payload', ['payload' => $payload]);


        try {
            $response = $this->client->post('v1/accounts', ['json' => $payload]);
            $data     = json_decode($response->getBody(), true);

            if (isset($data['id'])) {
                $recipient->update(['wise_recipient_id' => $data['id']]);
                return $data;
            }


            Log::error('Wise id not found in response', $data);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            Log::error('Wise API Error', $errorBody);
            throw $e;
        }
    }



    public function createQuote(User $user, Recipient $recipient, $amount)
    {
        Log::info('WISE: createQuote called', [
            'user_id' => $user->id,
            'recipient_id' => $recipient->id,
            'amount' => $amount
        ]);

        $payload = [
            'sourceCurrency' => 'USD',
            'targetCurrency' => $recipient->countryCurrency?->currency?->code,
            'sourceAmount' => $amount,
            'profile' => $user->wise_provider_id,
            'targetAccount' => $recipient->wise_recipient_id,
            'rateType' => 'FIXED',
        ];

        $response = $this->client->post('v3/quotes', ['json' => $payload]);
        $data = json_decode($response->getBody(), true);

        Log::info('WISE: createQuote response', $data);

        return $data;
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
