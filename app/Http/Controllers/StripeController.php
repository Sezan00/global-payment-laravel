<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function CheckOut(Request $request)
    {
        $user = Auth::user();
        $transaction = Transaction::with('targetCountryCurrency.currency')->where('id', $request->transaction_id)
            ->where('status', 'pending')->firstOrFail();

        if ($transaction->payment()->where('status', 'success')->exists()) {
            return response()->json([
                'message' => 'Transaction already paid.'
            ], 400);
        }

        $currencyCode = $transaction->targetCountryCurrency->currency->code;
        $amount = $transaction->converted_amount;

        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'reference' => uniqid('pay_'),
            'transaction_id' => $transaction->id,
            'status' => 'pending',
            'payment_method' => 'stripe',
        ]);

        $stripe = new StripeClient(config('services.stripe.secret'));

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $currencyCode,
                    'product_data' => [
                        'name' => 'Money Transfer'
                    ],
                    'unit_amount' => intval(round($amount * 100)),
                ],
                'quantity' => 1,
            ]],

            'mode' => 'payment',
            'ui_mode' => 'embedded',

            'metadata' => [
                'transaction_id' => $transaction->id,
                'payment_id' => $payment->id
            ],

            'return_url' => env('FRONTEND_URL') . '/payment/return?session_id={CHECKOUT_SESSION_ID}',
        ]);

        return response()->json([
            'clientSecret' => $checkout_session->client_secret
        ]);
    }
}
