<?php

namespace App\Http\Controllers;

use App\Models\CountryCurrencies;
use App\Models\Quotation;
use App\Models\Recipient;
use App\Models\RecipientAttribute;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecipientController extends Controller
{
    public function store(Request $request){
        $request->validate([
        'target_country_currency_id' => 'required|exists:country_currencies,id',
        'source_country_currency_id' => 'required|exists:country_currencies,id',
        'receive_type'               => 'required|string',
        'full_name'                  => 'required|string|max:30',
        'relation_id'                => 'required|exists:relations,id',
        'phone'                      => 'required|string',
        'email'                      => 'nullable|email',
        'city'                       => 'nullable|string|max:150',
        'address'                    => 'nullable|string|max:150',
        'post_code'                  => 'nullable|string|max:40',
        'bank_name'                  => 'nullable|string|max:30',
        'bank_account'               => 'nullable|string|max:30',
        'wallet_type'                => 'nullable|string|max:30',
        'wallet_number'              => 'nullable|string|max:30',
        'attributes.account_type' => 'required|string',
            'attributes.swift_code' => 'nullable|string',
            'attributes.legalType' => 'required|in:PRIVATE,BUSINESS',
        ]);

         $countryCurrency = CountryCurrencies::where('id', $request->target_country_currency_id)
                           ->whereIn('type', ['receiving', 'both'])->first();

        //   logger($countryCurrency);
        if(!$countryCurrency){
            return response()->json([
              'message' => 'Invalid currency for receiving'
            ],422); 
        }

        $recipient = Recipient::create([
            'user_id'        => Auth::id(),
            'source_country_currency_id' => $request->source_country_currency_id,
            'target_country_currency_id' =>  $request->target_country_currency_id,
            'receive_type'               => $request->receive_type,
            'full_name'                  => $request->full_name,
            'relation_id'                => $request->relation_id,
            'phone'                      => $request->phone,
            'email'                      => $request->email,
            'city'                       => $request->city,
            'address'                    => $request->address,
            'post_code'                  => $request->post_code,
            'bank_name'                  => $request->bank_name,
            'bank_account'               => $request->bank_account,
            'wallet_type'                => $request->wallet_type,
            'wallet_number'              => $request->wallet_number,
        ]);

        // if($request->has('attributes')){
        //     foreach($request->attributes as $key => $value){
        //         RecipientAttribute::create([
        //             'recipient_id' => $recipient->id,
        //             'key'          => $key,
        //             'value'        => $value,
        //         ]);
        //     }
        // }

                if($request->filled('attributes')){
                    foreach((array) $request->input('attributes') as $key => $value){
                        RecipientAttribute::create([
                            'recipient_id' => $recipient->id,
                            'key'          => $key,
                            'value'        => $value,
                        ]);
                    }
                }

        return response()->json([
          'message' => 'data submited',
          'Data'    => $recipient->load('attributes')
        ]);
    }   


    public function RecipientsList(Request $request){

        $quotationId = $request->query('quotation_id');

        if(!$quotationId){
            return response()->json([
                'data'    => [],
                'message' => 'Quation id not found'
            ],400);
        }

        $quotation = Quotation::find($quotationId);
        if(!$quotation){
            return response()->json([
                'data' => [],
                'message' => 'Quation not found'
            ]);
        }
        
        $recipients = Recipient::with('countryCurrency', 'relation')
                      ->where('user_id', Auth::id())
                      ->where('target_country_currency_id', $quotation->target_country_currency_id)
                      ->get();

        return response()->json([
            'data' => $recipients,
        ], 200);
    }



    public function index($id){
        $recipient = Recipient::with(
            'quotation.targetCurrency.country',
            'quotation.targetCurrency.currency',
            'countryCurrency.country',
            'countryCurrency.currency',
            'attributes',
            'sourceContryCurrency.currency'
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
        'relation_id',
        'target_country_currency_id',
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

    // if($request->field === 'target_country_currency_id'){
    //     $request->validate([
    //         'value' => 'required|exists:country_currencies,id'
    //     ]);
    // }

  if($request->field === 'target_country_currency_id' && $recipient->transactions()->exists()){
     return response()->json([
            'message' => 'Cannot change target_country_currency_id, recipient has transactions.'
        ], 422);
  } 

    $recipient->{$request->field} = $request->value;
    $recipient->save();

    return response()->json([
        'message' => 'Updated Successfully',
        'data' => $recipient
    ]);
}

    public function Destroy($id){
        $recipient = Recipient::findOrFail($id);

        if($recipient->transactions()->exists()){
            return response()->json([
                'message' => 'You cannot delete this recipient'
            ], 422);
        }
        $recipient->delete();

        return response()->json([
            'message' => 'Data Delete'
        ]);
    }

    public function showDashboardIndex(){

           $recipients = Recipient::with(
            'quotation.targetCurrency.country',
            'quotation.targetCurrency.currency'
            )->where('user_id', Auth::id() )
            ->get();

  

        return response()->json([
            'data' => $recipients,
        ], 200);
    }


    public function recipientFields($senderCountryCode, $recipientCountryCode){
        $filed = config("recipient_fields");

        if(!$filed[$senderCountryCode][$recipientCountryCode]){
            return response()->json([
                'message' => 'No fileds found'
            ], 404);
        }
        return response()->json($filed[$senderCountryCode][$recipientCountryCode]);
    }


}
