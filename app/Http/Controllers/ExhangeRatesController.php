<?php

namespace App\Http\Controllers;

use App\Models\ExhangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExhangeRatesController extends Controller
{
    public function ExchangeRateStore(Request $request){

        $request->validate([
            'source' => 'required|string',
            'target' => 'required|string',
            'converted_amount' => 'required|numeric'
        ]);

        $exhangeRate = ExhangeRate::create([
            'user_id' => Auth::id(),
            'sender_currency' => $request->source,
            'receiver_currency'=> $request->target,
            'converted_amount' => $request->converted_amount,
        ]);

        return response()->json([
            'message' => 'Exchange saved successfully',
            'data'    => $exhangeRate
        ], 201);
    }
}
