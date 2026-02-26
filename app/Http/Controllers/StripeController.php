<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPayoutJob;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function CheckOut(Request $request)
    {
        $user = Auth::user();
        logger('User stripe', ['user' => $user, $request->all()]);

        $transaction = Transaction::with('targetCountryCurrency.currency')->where('id', $request->transaction_id)->first();

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

            'return_url' => env('FRONTEND_URL') . '/payment-status?session_id={CHECKOUT_SESSION_ID}',
        ]);
        if (empty($payment->checkout_session)) {
            $payment->update([
                'checkout_session' => $checkout_session->id
            ]);
        }


        return response()->json([
            'clientSecret' => $checkout_session->client_secret,
            'sessionId' => $checkout_session->id
        ]);
    }

    public function getCheckoutSession(Request $request)
    {

        $sessionId = $request->session_id;
        logger('Session Id is ok or not:' . $sessionId);
        $stripe = new StripeClient(config('services.stripe.secret'));
        $checkout_session = $stripe->checkout->sessions->retrieve($sessionId);

        if (
            $checkout_session->payment_status == "paid" &&
            $checkout_session->status == "complete"
        ) {
            $transactionId = $checkout_session->metadata->transaction_id;
            logger('Transaction ID: ' . $transactionId);
            Transaction::where('id', $transactionId)
                ->update([
                    'status' => 'process'
                ]);
            ProcessPayoutJob::dispatch($transactionId);
        }
        return response()->json($checkout_session);
    }
}
