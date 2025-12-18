<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Recipient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecipientController extends Controller
{
    public function store(Request $request){
        // $request->validate([
        //   'target_country_currency_id' => 'required|exists:country_currencies,id',
        //   'receive_type'   => 'string',
        //   'full_name'      => 'string|max:30',
        //   'phone'          => 'string',
        //   'email'          => 'email',
        //   'city'           => 'string|max:150',
        //   'address'        => 'string|max:150',
        //   'bank_name'      => 'string|max:30',
        //   'bank_account'   => 'string|max:30',
        //   'wallet_type'    => 'string|max:30',
        //   'wallet_number'  => 'string|max:30',
        // ]);

        $quotation  = Quotation::findOrFail($request->quotation_id);
        $targetCountryCurrency = $quotation->target_country_currency_id;

        $recipient = Recipient::create([
            'user_id'        => Auth::id(),
            'target_country_currency_id' => $targetCountryCurrency,
            'receive_type'   => $request->receive_type,
            'full_name'      => $request->full_name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'city'           => $request->city,
            'address'        => $request->address,
            'bank_name'      => $request->bank_name,
            'bank_account'   => $request->bank_account,
            'wallet_type'    => $request->wallet_type,
            'wallet_number'  => $request->wallet_number,
        ]);

        $transacton = Transaction::where('quotation_id', $quotation->id)->first();
        if($transacton){
             $transacton->recipient_id = $recipient->id;
             $transacton->save();
        }

        return response()->json([
          'message' => 'data submited',
          'Data'    => $recipient
        ]);
    }   


    public function RecipientsList(){
        $recipiens = Recipient::with(
            'quotation.targetCurrency.country',
            'quotation.targetCurrency.currency'
            )->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'data' => $recipiens,
        ], 200);
    }



    public function index($id){
        $recipient = Recipient::with(
            'quotation.targetCurrency.country',
            'quotation.targetCurrency.currency'
            )->findOrFail($id);

       return response()->json([
        'Recipient' => $recipient
       ], 200);
    }

public function update(Request $request, $id){
    $recipient = Recipient::findOrFail($id);

    $allowedFields = [
        'receive_type',
        'full_name',
        'phone',
        'email',
        'city',
        'address',
        'bank_name',
        'bank_account',
        'wallet_type',
        'wallet_number',
    ];

    $request->validate([
        'field' => 'required|string',
        'value' => 'required'
    ]);

    if(!in_array($request->field, $allowedFields)){
        return response()->json([
            'message' => 'Invalid Field'
        ], 422);
    }

    $recipient->{$request->field} = $request->value;
    $recipient->save();

    return response()->json([
        'message' => 'Updated successfully',
        'data' => $recipient
    ]);
}

    public function Destroy($id){
        $recipient = Recipient::findOrFail($id);
        $recipient->delete();

        return response()->json([
            'message' => 'Data Delete'
        ]);
    }


}
