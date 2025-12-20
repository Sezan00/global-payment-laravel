<?php

namespace App\Http\Controllers;

use App\Models\CountryCurrencies;
use App\Models\Quotation;
use App\Models\Recipient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecipientController extends Controller
{
    public function store(Request $request){
        $request->validate([
        'target_country_currency_id' => 'required|exists:country_currencies,id',
        'receive_type'   => 'required|string',
        'full_name'      => 'required|string|max:30',
        'phone'          => 'required|string',
        'email'          => 'nullable|email',
        'city'           => 'nullable|string|max:150',
        'address'        => 'nullable|string|max:150',
        'bank_name'      => 'nullable|string|max:30',
        'bank_account'   => 'nullable|string|max:30',
        'wallet_type'    => 'nullable|string|max:30',
        'wallet_number'  => 'nullable|string|max:30',
        ]);

         $countryCurrency = CountryCurrencies::where('id', $request->target_country_currency_id)
                           ->whereIn('type', ['receiving', 'both'])->first();

        if(!$countryCurrency){
            return response()->json([
              'message' => 'Invalid currency for receiving'
            ],422); 
        }

        $recipient = Recipient::create([
            'user_id'        => Auth::id(),
            'target_country_currency_id' =>  $request->target_country_currency_id,
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
