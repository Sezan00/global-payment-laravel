<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessWiseTransfer;
use App\Models\Quotation;
use App\Models\Recipient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class TransactionController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'quotation_id' => "required|exists:quotations,id",
            'recipient_id' => 'required|exists:recipients,id',
        ]);

         $user = Auth::user();
         $recipient = Recipient::find($request->recipient_id);
         

        $quotation = Quotation::with('exhangeRate', 'sourceCurrency', 'targetCurrency')->findOrFail($request->quotation_id);

        $transaction = Transaction::create([
            'quotation_id'               => $quotation->id,
            'recipient_id'               => $request->recipient_id,
            'user_id'                    => Auth::id(),
            'source_country_currency_id' => $quotation->sourceCurrency->id,
            'target_country_currency_id' => $quotation->targetCurrency->id,
            'rate'                       => $quotation->exhangeRate->ex_rate,
            'amount'                     => $quotation->amount,
            'converted_amount'           => $quotation->amount * $quotation->exhangeRate->ex_rate
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => $transaction
        ]);
    }


    public function ShowTransaction($id){
         $transaction = Transaction::with(
            'quotation.sourceCurrency.country',
            'quotation.sourceCurrency.currency',
            'quotation.targetCurrency.country',
            'quotation.targetCurrency.currency',
            'recipient',
            'sourceOfFund',
            'user'
            )
            ->findOrFail($id);

         return response()->json([
            'transaction' => $transaction
         ]);
    }

   public function send($id){

    $transaction = Transaction::findOrFail($id);

    if ($transaction->status === 'processing') {
        return response()->json([
            'message' => 'Transfer already processing'
        ], 409);
    }

    ProcessWiseTransfer::dispatch(
        $transaction->user_id,
        $transaction->recipient_id,
        $transaction->id
    );

    return response()->json([
        'message' => 'Transfer processing started'
    ]);
}

}
