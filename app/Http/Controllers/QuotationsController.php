<?php

namespace App\Http\Controllers;

use App\Models\ExhangeRate;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationsController extends Controller
{
    public function store(Request $request){
        // $request->validate([
        //    'source_country_currency_id' => 'required|exists:country_currencies,id',
        //    'target_country_currency_id' => 'required|exists:country_currencies,id',
        //    'amount'                     => 'required|numeric',
        //    'exchange_rate_id'           => 'required|exists:exhange_rates,id'
        // ]);

         $rate = $request->input('exhange_rate_id') 
         ? ExhangeRate::find($request->input('exhange_rate_id')) 
         : ExhangeRate::latest('id')->first();

        if (!$rate) {
            return response()->json(['Error' => 'Rate not found'], 404);
        }


         $quotation = Quotation::create([
             'user_id' => Auth::id(),
             'source_country_currency_id' => $request->source_country_currency_id,
             'target_country_currency_id' => $request->target_country_currency_id,
             'amount'                     => $request->amount,
             'exhange_rate_id'            => $rate->id,
             'status'                     => 'pending',
         ]);

         return response()->json([
            'message' => 'Data submit to Quotation table',
             'data' => $quotation
         ]);
    }


    public function index($id){
        $quotation = Quotation::with(['sourceCurrency.currency', 'targetCurrency.currency', 'exhangeRate'])->find($id);

        if(!$quotation){
            return response()->json(['error'=> 'Data not found']);
        }

        return response()->json([
            'message' => 'Data got',
            'data'     => $quotation
        ]);
    }
}
