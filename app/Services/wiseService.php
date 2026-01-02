<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\User;

class WiseService
{
    public function connectUser(User $user)
    {
        $client = new Client();

        $response = $client->get(
            'https://api.sandbox.transferwise.tech/v1/me',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('WISE_API_TOKEN'),
                    'Content-Type'  => 'application/json',
                ]
            ]
        );

        $wiseUser = json_decode($response->getBody(), true);

        // Save Wise user
        $user->wise_provider_id = $wiseUser['id'];
        $user->save();

        return $wiseUser;
    }
}
