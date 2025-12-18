<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'quotation_id' => "required|exists:quotations,id",
            'recipient_id' => 'required|exists:recipients,id',
        ]);

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
}
